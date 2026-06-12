<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotMessage extends Model
{
    use HasFactory;

    protected $table = 'chatbot_messages';

    protected $fillable = [
        'session_id',
        'sender',
        'message',
    ];

    /**
     * Relationship with ChatbotSession.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatbotSession::class, 'session_id', 'id');
    }
}
