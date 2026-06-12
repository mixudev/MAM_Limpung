<?php

namespace Database\Factories;

use App\Models\ChatbotApiKey;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatbotApiKey>
 */
class ChatbotApiKeyFactory extends Factory
{
    protected $model = ChatbotApiKey::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider' => 'gemini',
            'model_name' => 'gemini-1.5-flash',
            'api_key' => 'AIzaSy'.fake()->regexify('[A-Za-z0-9_-]{33}'),
            'is_active' => true,
            'error_count' => 0,
            'limit_reached_at' => null,
        ];
    }
}
