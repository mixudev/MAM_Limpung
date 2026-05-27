<?php

namespace Database\Seeders;

use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class PrestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::whereEmail('admin@example.com')->first() ?? User::first();

        if (! $admin) {
            return;
        }

        $prestasis = [
            [
                'user_id' => $admin->id,
                'judul' => 'Juara 1 Olimpiade Matematika',
                'deskripsi' => 'Olimpiade Sains Nasional tingkat Provinsi Jawa Tengah yang diselenggarakan oleh Dinas Pendidikan.',
                'foto' => null,
                'tingkat' => 'provinsi',
                'jenis' => 'akademik',
                'penyelenggara' => 'Dinas Pendidikan Jawa Tengah',
                'peraih' => 'Ahmad Fauzan',
                'juara' => 'Juara 1',
                'tahun' => 2026,
                'tanggal_prestasi' => '2026-05-15',
                'is_featured' => true,
            ],
            [
                'user_id' => $admin->id,
                'judul' => 'Juara 2 Lomba Pidato Bahasa Arab',
                'deskripsi' => 'Pekan Keterampilan dan Seni Pendidikan Agama Islam (PENTAS PAI) Nasional.',
                'foto' => null,
                'tingkat' => 'nasional',
                'jenis' => 'akademik',
                'penyelenggara' => 'Kementerian Agama RI',
                'peraih' => 'Siti Nurhaliza',
                'juara' => 'Juara 2',
                'tahun' => 2026,
                'tanggal_prestasi' => '2026-04-10',
                'is_featured' => true,
            ],
            [
                'user_id' => $admin->id,
                'judul' => 'Juara 1 Turnamen Futsal Pelajar',
                'deskripsi' => 'Bupati Cup Antar SMA/MA Se-Kabupaten Batang dalam rangka memperingati HUT Kabupaten.',
                'foto' => null,
                'tingkat' => 'kabupaten',
                'jenis' => 'non_akademik',
                'penyelenggara' => 'Disparpora Kabupaten Batang',
                'peraih' => 'Tim Futsal MAM Limpung',
                'juara' => 'Juara 1',
                'tahun' => 2026,
                'tanggal_prestasi' => '2026-03-22',
                'is_featured' => true,
            ],
            [
                'user_id' => $admin->id,
                'judul' => 'Juara Harapan 1 Cipta Puisi',
                'deskripsi' => 'Festival Literasi Sekolah Tingkat Nasional untuk menumbuhkan minat kepenulisan siswa.',
                'foto' => null,
                'tingkat' => 'nasional',
                'jenis' => 'non_akademik',
                'penyelenggara' => 'Kemendikbudristek RI',
                'peraih' => 'Budi Santoso',
                'juara' => 'Juara Harapan 1',
                'tahun' => 2026,
                'tanggal_prestasi' => '2026-02-14',
                'is_featured' => false,
            ],
            [
                'user_id' => $admin->id,
                'judul' => 'Medali Perak Line Follower',
                'deskripsi' => 'Indonesian Robotic Olympiad (IRO) Tingkat Pelajar MA se-Indonesia.',
                'foto' => null,
                'tingkat' => 'nasional',
                'jenis' => 'akademik',
                'penyelenggara' => 'Persatuan Robotik Indonesia',
                'peraih' => 'Tim Robotik MAM Limpung',
                'juara' => 'Medali Perak',
                'tahun' => 2026,
                'tanggal_prestasi' => '2026-01-05',
                'is_featured' => false,
            ],
            [
                'user_id' => $admin->id,
                'judul' => 'Juara 3 Pencak Silat Kelas B Putri',
                'deskripsi' => 'Kejuaraan Daerah POPDA Provinsi Jawa Tengah tingkat SMA/MA/SMK.',
                'foto' => null,
                'tingkat' => 'provinsi',
                'jenis' => 'non_akademik',
                'penyelenggara' => 'Bapopsi Jawa Tengah',
                'peraih' => 'Rina Melati',
                'juara' => 'Juara 3',
                'tahun' => 2025,
                'tanggal_prestasi' => '2025-12-18',
                'is_featured' => false,
            ],
            [
                'user_id' => $admin->id,
                'judul' => 'Juara Umum Lomba Tingkat Penegak',
                'deskripsi' => 'Perkemahan Bakti Pramuka Kabupaten Batang dengan berbagai cabang perlombaan.',
                'foto' => null,
                'tingkat' => 'kabupaten',
                'jenis' => 'non_akademik',
                'penyelenggara' => 'Kwarcab Batang',
                'peraih' => 'Pramuka Ambalan MAM Limpung',
                'juara' => 'Juara Umum',
                'tahun' => 2025,
                'tanggal_prestasi' => '2025-11-10',
                'is_featured' => false,
            ],
        ];

        foreach ($prestasis as $prestasi) {
            Prestasi::updateOrCreate(
                ['judul' => $prestasi['judul'], 'peraih' => $prestasi['peraih']],
                $prestasi
            );
        }
    }
}
