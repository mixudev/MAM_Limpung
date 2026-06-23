<?php

namespace Database\Factories;

use App\Models\ChatbotKnowledgeBase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatbotKnowledgeBase>
 */
class ChatbotKnowledgeBaseFactory extends Factory
{
    protected $model = ChatbotKnowledgeBase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'content' => fake()->paragraph(4),
            'is_active' => true,
        ];
    }
}
