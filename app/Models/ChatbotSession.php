<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ChatbotSession extends Model
{
    use HasFactory;

    protected $table = 'chatbot_sessions';

    /**
     * Disable auto-incrementing since we are using UUID.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The key type for the primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'user_ip',
        'topic',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship with ChatbotMessage.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatbotMessage::class, 'session_id', 'id')->orderBy('created_at', 'asc');
    }

    /**
     * Relationship with User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
