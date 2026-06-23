<?php

namespace Database\Factories;

use App\Models\ChatbotFaq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatbotFaq>
 */
class ChatbotFaqFactory extends Factory
{
    protected $model = ChatbotFaq::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => fake()->sentence(6).'?',
            'answer' => fake()->paragraph(2),
            'order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
