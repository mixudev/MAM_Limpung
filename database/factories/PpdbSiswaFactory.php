<?php

namespace Database\Factories;

use App\Models\PpdbSiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PpdbSiswa>
 */
class PpdbSiswaFactory extends Factory
{
    protected $model = PpdbSiswa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_lengkap'   => $this->faker->name(),
            'nisn'           => $this->faker->unique()->numerify('##########'),
            'jenis_kelamin'  => $this->faker->randomElement(['L', 'P']),
            'tempat_lahir'   => $this->faker->city(),
            'tanggal_lahir'  => $this->faker->date('Y-m-d', '-15 years'),
            'nomor_hp'       => $this->faker->numerify('08##########'),
            'email'          => $this->faker->unique()->safeEmail(),
            'nama_ayah'      => $this->faker->name('male'),
            'nama_ibu'       => $this->faker->name('female'),
            'alamat_lengkap' => $this->faker->address(),
            'sekolah_asal'   => $this->faker->randomElement([
                'SMP Negeri 1 Limpung', 
                'MTs Muhammadiyah Limpung', 
                'SMP Negeri 2 Banyuputih',
                'SMP Muhammadiyah Limpung'
            ]),
            'ukuran_baju'    => $this->faker->randomElement(['S', 'M', 'L', 'XL', 'XXL', 'XXXL']),
            'foto_siswa'     => null,
            'status'         => 'pending',
            'catatan_admin'  => null,
            'submitted_at'   => now(),
        ];
    }
}
