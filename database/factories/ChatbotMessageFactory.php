<?php

namespace Database\Factories;

use App\Models\ChatbotMessage;
use App\Models\ChatbotSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatbotMessage>
 */
class ChatbotMessageFactory extends Factory
{
    protected $model = ChatbotMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'session_id' => ChatbotSession::factory(),
            'sender' => fake()->randomElement(['user', 'bot']),
            'message' => fake()->sentence(8),
        ];
    }
}
