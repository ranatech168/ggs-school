<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChatController extends Controller
{
    /**
     * Initialize or resume visitor chat session.
     */
    public function init(Request $request): JsonResponse
    {
        $sessionId = $request->input('session_id');
        $existing = $sessionId ? ChatConversation::where('session_id', $sessionId)->first() : null;

        // If conversation does not exist or has no name/phone, require name and phone
        $requiresInfo = ! $existing || empty($existing->visitor_name) || empty($existing->visitor_phone);

        $rules = [
            'session_id' => 'nullable|string|max:100',
            'visitor_email' => 'nullable|email|max:150',
            'visitor_name' => $requiresInfo ? 'required|string|max:100' : 'nullable|string|max:100',
            'visitor_phone' => $requiresInfo ? 'required|string|max:50' : 'nullable|string|max:50',
        ];

        $validated = $request->validate($rules);

        $sessionId = !empty($validated['session_id']) ? $validated['session_id'] : (string) Str::uuid();

        $conversation = ChatConversation::firstOrCreate(
            ['session_id' => $sessionId],
            [
                'visitor_name' => $validated['visitor_name'] ?? 'Visitor',
                'visitor_phone' => $validated['visitor_phone'] ?? null,
                'visitor_email' => $validated['visitor_email'] ?? null,
                'status' => 'active',
                'last_message_at' => now(),
            ]
        );

        if (!empty($validated['visitor_name'])) {
            $conversation->visitor_name = $validated['visitor_name'];
        }
        if (!empty($validated['visitor_phone'])) {
            $conversation->visitor_phone = $validated['visitor_phone'];
        }
        if (!empty($validated['visitor_email'])) {
            $conversation->visitor_email = $validated['visitor_email'];
        }
        $conversation->save();

        // If conversation is brand new, send an automated welcome bot greeting
        if ($conversation->messages()->count() === 0) {
            $displayName = $conversation->visitor_name ?: 'Visitor';
            $phoneNote = $conversation->visitor_phone ? " (Phone: {$conversation->visitor_phone})" : '';
            $conversation->messages()->create([
                'sender_type' => 'bot',
                'sender_name' => 'GGS Assistant',
                'message' => "Welcome to Global Generation School (GGS), {$displayName}! 👋\n\nThank you for connecting with us{$phoneNote}. Our admissions team and administrators have been notified.\n\nHow can we help you today? You can ask about our bilingual curriculum, admissions, tuition fees, campus locations, or leave a message for our staff!",
                'is_read' => true,
            ]);
            $conversation->update(['last_message_at' => now()]);
        }

        $messages = $conversation->messages()->get()->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_type' => $msg->sender_type,
                'sender_name' => $msg->sender_name,
                'message' => $msg->message,
                'created_at' => $msg->created_at->format('g:i A'),
                'created_date' => $msg->created_at->format('M d, Y'),
            ];
        });

        return response()->json([
            'session_id' => $conversation->session_id,
            'visitor_name' => $conversation->visitor_name,
            'visitor_phone' => $conversation->visitor_phone,
            'status' => $conversation->status,
            'messages' => $messages,
        ]);
    }

    /**
     * Poll messages for frontend visitor.
     */
    public function visitorMessages(Request $request): JsonResponse
    {
        $sessionId = $request->query('session_id');
        if (! $sessionId) {
            return response()->json(['error' => 'Missing session_id'], 400);
        }

        $conversation = ChatConversation::where('session_id', $sessionId)->first();
        if (! $conversation) {
            return response()->json(['messages' => []]);
        }

        // Mark staff and bot messages as read by the visitor
        $conversation->messages()
            ->whereIn('sender_type', ['staff', 'bot'])
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()->get()->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_type' => $msg->sender_type,
                'sender_name' => $msg->sender_name,
                'message' => $msg->message,
                'created_at' => $msg->created_at->format('g:i A'),
                'created_date' => $msg->created_at->format('M d, Y'),
            ];
        });

        return response()->json([
            'status' => $conversation->status,
            'messages' => $messages,
        ]);
    }

    /**
     * Visitor sends a message.
     */
    public function visitorSend(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => 'required|string|max:100',
            'message' => 'required|string|max:2000',
            'visitor_name' => 'nullable|string|max:100',
            'visitor_phone' => 'nullable|string|max:50',
        ]);

        $conversation = ChatConversation::where('session_id', $validated['session_id'])->first();

        // Ensure visitor provides Name and Phone number before chatting
        if (! $conversation || empty($conversation->visitor_phone) || empty($conversation->visitor_name)) {
            $name = $validated['visitor_name'] ?? ($conversation->visitor_name ?? null);
            $phone = $validated['visitor_phone'] ?? ($conversation->visitor_phone ?? null);

            if (empty($name) || empty($phone)) {
                return response()->json([
                    'message' => 'Please enter your Name and Phone Number before sending a message.',
                    'requires_registration' => true,
                ], 422);
            }

            if (! $conversation) {
                $conversation = ChatConversation::create([
                    'session_id' => $validated['session_id'],
                    'visitor_name' => $name,
                    'visitor_phone' => $phone,
                    'status' => 'active',
                    'last_message_at' => now(),
                ]);
            } else {
                $conversation->visitor_name = $name;
                $conversation->visitor_phone = $phone;
            }
        }

        if (! empty($validated['visitor_name'])) {
            $conversation->visitor_name = $validated['visitor_name'];
        }
        if (! empty($validated['visitor_phone'])) {
            $conversation->visitor_phone = $validated['visitor_phone'];
        }

        $conversation->status = 'active';
        $conversation->last_message_at = now();
        $conversation->save();

        $userMessage = $conversation->messages()->create([
            'sender_type' => 'visitor',
            'sender_name' => $conversation->visitor_name ?: 'Visitor',
            'message' => trim($validated['message']),
            'is_read' => false, // unread for staff
        ]);

        // Check if Chatbot should generate an instant automated answer
        $botReplyText = $this->getBotAnswer(trim($validated['message']), $conversation);
        if ($botReplyText) {
            $conversation->messages()->create([
                'sender_type' => 'bot',
                'sender_name' => 'GGS Assistant',
                'message' => $botReplyText,
                'is_read' => false,
            ]);
            $conversation->update(['last_message_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'user_message' => [
                'id' => $userMessage->id,
                'sender_type' => $userMessage->sender_type,
                'sender_name' => $userMessage->sender_name,
                'message' => $userMessage->message,
                'created_at' => $userMessage->created_at->format('g:i A'),
            ],
            'bot_reply' => $botReplyText ? true : false,
        ]);
    }

    /**
     * Smart bot answer generator for common inquiries.
     */
    protected function getBotAnswer(string $input, ChatConversation $conversation): ?string
    {
        $lower = mb_strtolower($input);

        // 1. Tuition / Fees
        if (Str::contains($lower, ['tuition', 'fee', 'price', 'cost', 'how much', 'តម្លៃ', 'ថ្លៃ', 'បង់ថ្លៃ'])) {
            return "💰 **Tuition & Enrollment Fees**:\n\nOur tuition fees are tailored to each educational stage (Kindergarten, Primary, Lower Secondary, and High School) with flexible installment options and sibling discounts.\n\nOur admissions office will provide the full fee schedule. You can also call us directly at **088 6666 288** or leave your contact details here!";
        }

        // 2. Programs / Curriculum
        if (Str::contains($lower, ['curriculum', 'program', 'course', 'bilingual', 'cambridge', 'kindergarten', 'primary', 'secondary', 'high school', 'កម្មវិធី', 'ភាសា', 'អង់គ្លេស'])) {
            return "🎓 **Academic Programs**:\n\nGGS provides an accredited 21st-century bilingual curriculum integrating:\n• MoEYS National Khmer Curriculum\n• Cambridge International English Curriculum\n• STEAM, Robotics & Digital Computing\n• Character building & Leadership\n\nWould you like more details for a specific grade level?";
        }

        // 3. Location / Campus / Address / Map
        if (Str::contains($lower, ['location', 'campus', 'where', 'address', 'map', 'directions', 'siem reap', 'ទីតាំង', 'នៅឯណា', 'ផែនទី', 'ផ្លូវ'])) {
            return "📍 **Campus Location**:\n\nGlobal Generation School is located in Siem Reap (Lomdab Thmey / Svay Dangkum).\n\nYou can find our exact Google Maps location by clicking the satellite map in the **Explore Campus Locations** section on our homepage, or directly via Google Maps!";
        }

        // 4. Admission / How to apply / Register
        if (Str::contains($lower, ['admission', 'enroll', 'apply', 'register', 'sign up', 'ចុះឈ្មោះ', 'ចូលរៀន', 'កាលបរិច្ឆេទ'])) {
            return "📝 **Admissions 2026-2027**:\n\nAdmissions are currently open for new students!\n\nTo enroll:\n1. Fill out the Campus Tour form on our homepage, or\n2. Message our team right here with your child's age and desired grade.\n\nOur admissions staff will guide you through the process step-by-step!";
        }

        // 5. Contact / Phone / Email / Hours
        if (Str::contains($lower, ['contact', 'phone', 'call', 'email', 'hours', 'open', 'time', 'លេខទូរស័ព្ទ', 'ម៉ោងធ្វើការ', 'ទាក់ទង'])) {
            return "📞 **Contact Information**:\n\n• **Phone**: 088 6666 288 / 012 452 837\n• **Email**: ggschool2020@gmail.com\n• **Office Hours**: Monday to Saturday, 7:30 AM – 5:00 PM\n\nOur staff are also monitoring this chat!";
        }

        // If this is the visitor's first or second visitor message, provide general reassurance
        $visitorMsgCount = $conversation->messages()->where('sender_type', 'visitor')->count();
        if ($visitorMsgCount <= 1) {
            return "Thank you for contacting Global Generation School! 🏫\n\nYour message has been received. Our team (Editors & Super Administrators) has been notified and will reply to you here shortly.\n\nFeel free to leave your name or phone number so we can best follow up with you!";
        }

        return null;
    }

    // ==========================================
    // BACKEND STAFF (EDITOR & SUPER ADMIN)
    // ==========================================

    /**
     * Display live chat admin dashboard.
     */
    public function adminIndex(): View
    {
        return view('admin.chat');
    }

    /**
     * Admin: Fetch conversations list (for sidebar & polling).
     */
    public function adminConversations(Request $request): JsonResponse
    {
        $status = $request->query('status', 'all');
        $query = ChatConversation::withCount(['unreadMessagesForStaff as unread_count'])
            ->with(['latestMessage']);

        if ($status === 'active') {
            $query->where('status', 'active');
        } elseif ($status === 'closed') {
            $query->where('status', 'closed');
        }

        $conversations = $query->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->limit(50)
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'session_id' => $c->session_id,
                    'visitor_name' => $c->visitor_name,
                    'visitor_email' => $c->visitor_email,
                    'visitor_phone' => $c->visitor_phone,
                    'status' => $c->status,
                    'unread_count' => $c->unread_count,
                    'last_message' => $c->latestMessage ? Str::limit($c->latestMessage->message, 60) : 'No messages',
                    'last_message_time' => $c->last_message_at ? $c->last_message_at->diffForHumans() : $c->updated_at->diffForHumans(),
                ];
            });

        $totalUnread = ChatMessage::where('sender_type', 'visitor')
            ->where('is_read', false)
            ->count();

        return response()->json([
            'conversations' => $conversations,
            'total_unread' => $totalUnread,
        ]);
    }

    /**
     * Admin: Get all messages for a specific conversation and mark visitor messages as read.
     */
    public function adminShow(ChatConversation $conversation): JsonResponse
    {
        // Mark all visitor messages in this conversation as read
        $conversation->messages()
            ->where('sender_type', 'visitor')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()->get()->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_type' => $msg->sender_type,
                'sender_name' => $msg->sender_name,
                'message' => $msg->message,
                'is_read' => $msg->is_read,
                'created_at' => $msg->created_at->format('g:i A'),
                'created_date' => $msg->created_at->format('M d, Y'),
            ];
        });

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'session_id' => $conversation->session_id,
                'visitor_name' => $conversation->visitor_name,
                'visitor_email' => $conversation->visitor_email,
                'visitor_phone' => $conversation->visitor_phone,
                'status' => $conversation->status,
                'created_at' => $conversation->created_at->format('M d, Y g:i A'),
            ],
            'messages' => $messages,
        ]);
    }

    /**
     * Admin/Editor: Reply to visitor conversation.
     */
    public function adminReply(Request $request, ChatConversation $conversation): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:3000',
        ]);

        $user = auth()->user();
        $senderName = $user ? $user->name : 'Staff Support';

        $message = $conversation->messages()->create([
            'sender_type' => 'staff',
            'sender_id' => $user ? $user->id : null,
            'sender_name' => $senderName,
            'message' => trim($validated['message']),
            'is_read' => false,
        ]);

        $conversation->update([
            'status' => 'active',
            'last_message_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_type' => $message->sender_type,
                'sender_name' => $message->sender_name,
                'message' => $message->message,
                'created_at' => $message->created_at->format('g:i A'),
            ],
        ]);
    }

    /**
     * Admin/Editor: Toggle or update status (active / closed).
     */
    public function adminToggleStatus(Request $request, ChatConversation $conversation): JsonResponse
    {
        $newStatus = $conversation->status === 'active' ? 'closed' : 'active';
        $conversation->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => "Conversation marked as {$newStatus}.",
        ]);
    }

    /**
     * Admin/Editor: Delete conversation.
     */
    public function adminDestroy(ChatConversation $conversation): JsonResponse
    {
        $conversation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Conversation deleted successfully.',
        ]);
    }
}
