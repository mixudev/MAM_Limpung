<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\RegistrationWave;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RegistrationWave>
 */
class RegistrationWaveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+3 months');

        return [
            'academic_year_id' => AcademicYear::factory(),
            'slug' => $this->faker->slug(),
            'name' => 'Gelombang '.$this->faker->numberBetween(1, 5),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => (clone $startDate)->modify('+'.rand(30, 90).' days')->format('Y-m-d'),
            'is_active' => true,
        ];
    }
}
