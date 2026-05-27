<?php

namespace Database\Seeders;

use App\Models\ArticleCategory;
use Illuminate\Database\Seeder;

class ArticleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Berita Sekolah',
                'description' => 'Kumpulan berita terkini seputar kegiatan belajar mengajar dan event di MA Muhammadiyah Limpung.',
            ],
            [
                'name' => 'Pengumuman Resmi',
                'description' => 'Informasi penting dan instruksi resmi dari pihak manajemen sekolah untuk siswa, guru, dan wali murid.',
            ],
            [
                'name' => 'Prestasi & Penghargaan',
                'description' => 'Apresiasi dan catatan prestasi akademik maupun non-akademik yang diraih oleh siswa dan guru.',
            ],
            [
                'name' => 'Opini & Artikel Ilmiah',
                'description' => 'Wadah karya tulis ilmiah populer, esai, dan opini dari guru dan siswa MA Muhammadiyah Limpung.',
            ],
            [
                'name' => 'Ekstrakurikuler & Keagamaan',
                'description' => 'Berita dan dokumentasi kegiatan kepanduan HW, IPM, Tapak Suci, kajian, dan pembinaan keislaman.',
            ],
        ];

        foreach ($categories as $category) {
            ArticleCategory::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
