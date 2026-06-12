<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotApiKey extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'chatbot_api_keys';

    protected $fillable = [
        'provider',
        'model_name',
        'api_key',
        'is_active',
        'error_count',
        'limit_reached_at',
    ];

    /**
     * Get the casts array.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'api_key' => 'encrypted',
            'is_active' => 'boolean',
            'limit_reached_at' => 'datetime',
            'error_count' => 'integer',
        ];
    }
}
