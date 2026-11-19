<?php

namespace Tests\Feature;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    private function superAdmin(): User
    {
        $role = Role::firstOrCreate(['slug' => 'super-admin'], ['name' => 'Super administrator']);
        $perm = Permission::firstOrCreate(['slug' => 'view-messages'], ['name' => 'Read contact messages']);
        $role->permissions()->syncWithoutDetaching([$perm->id]);

        return User::firstOrCreate(['email' => 'admin@school.test'], [
            'name' => 'Super Admin',
            'password' => 'password12345',
            'role_id' => $role->id,
        ]);
    }

    private function editor(): User
    {
        $role = Role::firstOrCreate(['slug' => 'editor'], ['name' => 'Editor']);
        $perm = Permission::firstOrCreate(['slug' => 'view-messages'], ['name' => 'Read contact messages']);
        $role->permissions()->syncWithoutDetaching([$perm->id]);

        return User::firstOrCreate(['email' => 'editor@school.test'], [
            'name' => 'Editor User',
            'password' => 'password12345',
            'role_id' => $role->id,
        ]);
    }

    public function test_visitor_must_provide_name_and_phone_to_init_chat(): void
    {
        // 1. Missing both name and phone
        $this->postJson('/chat/init', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['visitor_name', 'visitor_phone']);

        // 2. Missing phone
        $this->postJson('/chat/init', ['visitor_name' => 'Sokha'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['visitor_phone']);

        // 3. Valid name and phone
        $response = $this->postJson('/chat/init', [
            'visitor_name' => 'Sokha Chann',
            'visitor_phone' => '088 6666 288',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['session_id', 'visitor_name', 'visitor_phone', 'status', 'messages']);

        $data = $response->json();
        $this->assertNotEmpty($data['session_id']);
        $this->assertSame('Sokha Chann', $data['visitor_name']);
        $this->assertSame('088 6666 288', $data['visitor_phone']);
        $this->assertCount(1, $data['messages']);
        $this->assertSame('bot', $data['messages'][0]['sender_type']);
        $this->assertStringContainsString('Welcome to Global Generation School', $data['messages'][0]['message']);
        $this->assertStringContainsString('Sokha Chann', $data['messages'][0]['message']);

        $this->assertDatabaseHas('chat_conversations', [
            'session_id' => $data['session_id'],
            'visitor_name' => 'Sokha Chann',
            'visitor_phone' => '088 6666 288',
        ]);
    }

    public function test_visitor_cannot_send_message_without_registered_name_and_phone(): void
    {
        // Random session not initialized with phone
        $res = $this->postJson('/chat/send', [
            'session_id' => 'unregistered-session-id',
            'message' => 'Hello without registering',
        ]);

        $res->assertStatus(422)
            ->assertJson(['requires_registration' => true]);

        $this->assertDatabaseMissing('chat_messages', [
            'message' => 'Hello without registering',
        ]);
    }

    public function test_visitor_can_send_message_and_trigger_bot_faq_response(): void
    {
        $init = $this->postJson('/chat/init', [
            'visitor_name' => 'Dara Meas',
            'visitor_phone' => '012 345 678',
        ])->json();
        $sessionId = $init['session_id'];

        $sendResponse = $this->postJson('/chat/send', [
            'session_id' => $sessionId,
            'visitor_name' => 'Dara Meas',
            'visitor_phone' => '012 345 678',
            'message' => 'What is the tuition fee for primary school?',
        ]);

        $sendResponse->assertOk()
            ->assertJson(['success' => true, 'bot_reply' => true]);

        $this->assertDatabaseHas('chat_messages', [
            'sender_type' => 'visitor',
            'message' => 'What is the tuition fee for primary school?',
        ]);

        $this->assertDatabaseHas('chat_messages', [
            'sender_type' => 'bot',
        ]);

        $poll = $this->getJson('/chat/messages?session_id=' . $sessionId);
        $poll->assertOk();
        $messages = $poll->json('messages');
        $this->assertGreaterThanOrEqual(3, count($messages)); // welcome + visitor + bot FAQ answer
    }

    public function test_unauthenticated_or_unauthorized_users_cannot_access_admin_chat(): void
    {
        $this->get('/admin/chat')->assertRedirect('/login');

        $roleNoPerm = Role::create(['name' => 'Guest Role', 'slug' => 'guest-role']);
        $userNoPerm = User::create([
            'name' => 'Guest User',
            'email' => 'guest@school.test',
            'password' => 'password12345',
            'role_id' => $roleNoPerm->id,
        ]);

        $this->actingAs($userNoPerm)->get('/admin/chat')->assertForbidden();
    }

    public function test_super_admin_and_editor_can_access_admin_chat_and_view_visitor_phone(): void
    {
        $admin = $this->superAdmin();
        $editor = $this->editor();

        $conversation = ChatConversation::create([
            'session_id' => 'test-uuid-1234',
            'visitor_name' => 'Pros Keo',
            'visitor_phone' => '099 888 777',
            'status' => 'active',
            'last_message_at' => now(),
        ]);
        $conversation->messages()->create([
            'sender_type' => 'visitor',
            'sender_name' => 'Pros Keo',
            'message' => 'Hello, I have a question about admissions.',
            'is_read' => false,
        ]);

        // Admin checks
        $this->actingAs($admin)->get('/admin/chat')->assertOk();
        $res = $this->actingAs($admin)->getJson('/admin/chat/conversations');
        $res->assertOk()
            ->assertJsonFragment([
                'visitor_name' => 'Pros Keo',
                'visitor_phone' => '099 888 777',
            ]);

        // Editor checks
        $this->actingAs($editor)->get('/admin/chat')->assertOk();
        $res2 = $this->actingAs($editor)->getJson('/admin/chat/conversations');
        $res2->assertOk()
            ->assertJsonFragment([
                'visitor_name' => 'Pros Keo',
                'visitor_phone' => '099 888 777',
            ]);

        // Conversation detail includes phone
        $showRes = $this->actingAs($admin)->getJson('/admin/chat/conversations/' . $conversation->id);
        $showRes->assertOk()
            ->assertJsonPath('conversation.visitor_phone', '099 888 777');
    }

    public function test_admin_and_editor_can_reply_to_visitor_and_visitor_receives_reply(): void
    {
        $editor = $this->editor();

        $conversation = ChatConversation::create([
            'session_id' => 'visitor-session-xyz',
            'visitor_name' => 'Chanthy',
            'visitor_phone' => '087 111 222',
            'status' => 'active',
            'last_message_at' => now(),
        ]);
        $conversation->messages()->create([
            'sender_type' => 'visitor',
            'sender_name' => 'Chanthy',
            'message' => 'Can I schedule a school visit tomorrow?',
            'is_read' => false,
        ]);

        // Editor replies from backend
        $replyRes = $this->actingAs($editor)->postJson("/admin/chat/conversations/{$conversation->id}/reply", [
            'message' => 'Yes, our campus is open from 7:30 AM to 5:00 PM tomorrow. Looking forward to meeting you!',
        ]);

        $replyRes->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseHas('chat_messages', [
            'chat_conversation_id' => $conversation->id,
            'sender_type' => 'staff',
            'sender_name' => 'Editor User',
            'sender_id' => $editor->id,
        ]);

        // Frontend visitor polls and receives reply
        $poll = $this->getJson('/chat/messages?session_id=' . $conversation->session_id);
        $poll->assertOk();
        $msgs = $poll->json('messages');
        $lastMsg = end($msgs);
        $this->assertSame('staff', $lastMsg['sender_type']);
        $this->assertStringContainsString('7:30 AM', $lastMsg['message']);
    }

    public function test_admin_can_toggle_status_and_delete_conversation(): void
    {
        $admin = $this->superAdmin();

        $conversation = ChatConversation::create([
            'session_id' => 'delete-test-uuid',
            'visitor_name' => 'Test Visitor',
            'visitor_phone' => '012 999 888',
            'status' => 'active',
        ]);

        // Toggle status
        $this->actingAs($admin)->postJson("/admin/chat/conversations/{$conversation->id}/toggle-status")
            ->assertOk()
            ->assertJson(['success' => true, 'status' => 'closed']);

        $this->assertSame('closed', $conversation->fresh()->status);

        // Delete conversation
        $this->actingAs($admin)->deleteJson("/admin/chat/conversations/{$conversation->id}")
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('chat_conversations', ['id' => $conversation->id]);
    }

    public function test_frontend_renders_chat_widget_and_admin_layout_shows_live_chat_link(): void
    {
        $this->seed();
        $this->get('/')->assertOk()->assertSee('id="ggs-chat-button"', false);

        $admin = $this->superAdmin();
        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Live Chat');

        $editor = $this->editor();
        $this->actingAs($editor)->get('/admin')->assertOk()->assertSee('Live Chat');
    }
}
