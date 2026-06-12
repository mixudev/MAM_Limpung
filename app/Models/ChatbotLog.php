<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotLog extends Model
{
    use HasFactory;

    protected $table = 'chatbot_logs';

    /**
     * Disable the default updated_at column since logs are insert-only.
     */
    const UPDATED_AT = null;

    protected $fillable = [
        'api_key_id',
        'session_id',
        'level',
        'message',
        'payload',
    ];

    /**
     * Get the casts array.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the API key used when generating this log.
     */
    public function apiKey(): BelongsTo
    {
        return $this->belongsTo(ChatbotApiKey::class, 'api_key_id');
    }

    /**
     * Get the chatbot session associated with this log.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatbotSession::class, 'session_id');
    }
}
