<?php

namespace Database\Seeders;

use App\Models\TeacherCategory;
use Illuminate\Database\Seeder;

class TeacherCategorySeeder extends Seeder
{
    private array $categories = [
        ['name' => 'Kepala Sekolah',     'description' => 'Kepala Madrasah / Sekolah, pemimpin tertinggi di lingkungan madrasah.'],
        ['name' => 'Wakil Kepala Sekolah', 'description' => 'Wakil Kepala Madrasah yang membantu tugas Kepala Sekolah.'],
        ['name' => 'Guru',               'description' => 'Tenaga pendidik pengampu mata pelajaran.'],
        ['name' => 'Staff',              'description' => 'Staf tata usaha dan tenaga kependidikan non-pengajar.'],
    ];

    public function run(): void
    {
        foreach ($this->categories as $cat) {
            TeacherCategory::firstOrCreate(
                ['slug' => \Str::slug($cat['name'])],
                $cat
            );
        }
    }
}
