<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ChatConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'visitor_name',
        'visitor_email',
        'visitor_phone',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_conversation_id')->oldest();
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(ChatMessage::class, 'chat_conversation_id')->latestOfMany();
    }

    public function unreadMessagesForStaff(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_conversation_id')
            ->where('sender_type', 'visitor')
            ->where('is_read', false);
    }

    public function unreadCountForStaff(): int
    {
        return $this->unreadMessagesForStaff()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

