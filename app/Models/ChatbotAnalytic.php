<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotAnalytic extends Model
{
    use HasFactory;

    protected $table = 'chatbot_analytics';

    /**
     * Disable timestamps since we only use created_at.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'session_id',
        'query',
        'response',
        'response_time_ms',
        'tokens_used',
        'api_key_used_id',
        'feedback',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->created_at = $model->created_at ?? now();
        });
    }

    /**
     * Relationship with ChatbotSession.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatbotSession::class, 'session_id', 'id');
    }

    /**
     * Relationship with ChatbotApiKey.
     */
    public function apiKey(): BelongsTo
    {
        return $this->belongsTo(ChatbotApiKey::class, 'api_key_used_id', 'id');
    }
}
