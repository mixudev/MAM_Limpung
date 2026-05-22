<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => ArticleCategory::factory(),
            'judul' => $this->faker->unique()->sentence(6),
            'ringkasan' => $this->faker->paragraph(2),
            'konten' => '<p>'.implode('</p><p>', $this->faker->paragraphs(4)).'</p>',
            'thumbnail' => null,
            'status' => 'draft',
            'published_at' => null,
        ];
    }

    /**
     * State: article is published (visible on frontend).
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
    }

    /**
     * State: article is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * State: article is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
            'published_at' => null,
        ]);
    }
}
