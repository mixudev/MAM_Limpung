<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = (int) $this->faker->unique()->numberBetween(2020, 2030);

        return [
            'year' => $year,
            'name' => $year.'/'.($year + 1),
            'is_active' => false,
        ];
    }
}
