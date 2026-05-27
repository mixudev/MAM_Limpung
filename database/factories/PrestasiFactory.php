<?php

namespace Database\Factories;

use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prestasi>
 */
class PrestasiFactory extends Factory
{
    protected $model = Prestasi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tingkat = $this->faker->randomElement(['sekolah', 'kabupaten', 'provinsi', 'nasional', 'internasional']);
        $jenis = $this->faker->randomElement(['akademik', 'non_akademik']);
        $tahun = $this->faker->numberBetween(2024, 2026);

        return [
            'user_id' => User::factory(),
            'judul' => $this->faker->sentence(4),
            'deskripsi' => $this->faker->paragraph(),
            'foto' => null,
            'tingkat' => $tingkat,
            'jenis' => $jenis,
            'penyelenggara' => $this->faker->company().' Indonesia',
            'peraih' => $this->faker->name(),
            'juara' => $this->faker->randomElement(['Juara 1', 'Juara 2', 'Juara 3', 'Juara Harapan 1', 'Medali Emas', 'Medali Perak']),
            'tahun' => $tahun,
            'tanggal_prestasi' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
        ];
    }

    /**
     * State: achievement is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
