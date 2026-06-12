<?php

namespace Database\Factories;

use App\Models\ChatbotAnalytic;
use App\Models\ChatbotApiKey;
use App\Models\ChatbotSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatbotAnalytic>
 */
class ChatbotAnalyticFactory extends Factory
{
    protected $model = ChatbotAnalytic::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'session_id' => ChatbotSession::factory(),
            'query' => fake()->sentence(6).'?',
            'response' => fake()->paragraph(1),
            'topic' => fake()->randomElement(['ppdb', 'kegiatan', 'bantuan', 'umum']),
            'response_time_ms' => fake()->numberBetween(100, 1500),
            'tokens_used' => fake()->numberBetween(50, 400),
            'api_key_used_id' => ChatbotApiKey::factory(),
            'feedback' => fake()->randomElement(['like', 'dislike', null]),
            'created_at' => now()->subDays(fake()->numberBetween(0, 30)),
        ];
    }
}
