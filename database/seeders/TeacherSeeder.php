<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\TeacherCategory;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $categories = TeacherCategory::pluck('id', 'slug');

        $teachers = [
            // Komite Madrasah
            [
                'nama' => 'Miyatun',
                'jenis_kelamin' => 'P',
                'category_slugs' => ['komite-madrasah'],
            ],

            // Kepala Madrasah
            [
                'nama' => 'Muniroch, M.Pd.',
                'jenis_kelamin' => 'P',
                'category_slugs' => ['kepala-madrasah'],
            ],

            // Wakil Kepala Madrasah
            [
                'nama' => 'Anna Nur Elawati, S.Pd.',
                'jenis_kelamin' => 'P',
                'category_slugs' => ['waka-kurikulum', 'wali-kelas-x-a'],
            ],
            [
                'nama' => 'Ahyaudin, S.Pd.I.',
                'jenis_kelamin' => 'L',
                'category_slugs' => ['waka-kesiswaan', 'wali-kelas-xi-b'],
            ],
            [
                'nama' => 'Hj. Riza Ariyati Mufidah, S.Pd.',
                'jenis_kelamin' => 'P',
                'category_slugs' => ['waka-sarpras', 'wali-kelas-xii-a'],
            ],

            // Tata Usaha
            [
                'nama' => 'Fariz Arsyad, S.Psi.',
                'jenis_kelamin' => 'L',
                'category_slugs' => ['kepala-tata-usaha', 'guru-bk'],
            ],
            [
                'nama' => 'Amirul Amin A.',
                'jenis_kelamin' => 'L',
                'category_slugs' => ['staf-tata-usaha'],
            ],
            [
                'nama' => 'Jalu Darpita A. K.',
                'jenis_kelamin' => 'L',
                'category_slugs' => ['staf-tata-usaha'],
            ],

            // Operator
            [
                'nama' => 'Listyo Permadi, S.Pd.',
                'jenis_kelamin' => 'L',
                'category_slugs' => ['operator-madrasah', 'kepala-lab-komputer'],
            ],

            // Bendahara
            [
                'nama' => 'Heni Tri Wardani, S.Pd.',
                'jenis_kelamin' => 'P',
                'category_slugs' => ['bendahara-madrasah', 'wali-kelas-xi-a'],
            ],
            [
                'nama' => 'Nur Hidayah, S.Pd.',
                'jenis_kelamin' => 'P',
                'category_slugs' => ['bendahara-bos', 'kepala-lab-ipa', 'wali-kelas-xii-b'],
            ],

            // Unit Pendukung
            [
                'nama' => 'Mukaromatul Ulya, S.Pd.',
                'jenis_kelamin' => 'P',
                'category_slugs' => ['kepala-perpustakaan'],
            ],
            [
                'nama' => 'M. Nova Ibnu S.',
                'jenis_kelamin' => 'L',
                'category_slugs' => ['satpam-madrasah'],
            ],

            // Wali Kelas
            [
                'nama' => 'Hana Rizayanti, M.Pd.',
                'jenis_kelamin' => 'P',
                'category_slugs' => ['wali-kelas-x-b'],
            ],

            // Dewan Guru
            [
                'nama' => 'H. Anton Khaerudianto, S.Pd.',
                'jenis_kelamin' => 'L',
                'category_slugs' => ['dewan-guru'],
            ],
            [
                'nama' => 'Kusworini, S.Pd.',
                'jenis_kelamin' => 'P',
                'category_slugs' => ['dewan-guru'],
            ],
            [
                'nama' => 'Hj. Indahwati, S.E.',
                'jenis_kelamin' => 'P',
                'category_slugs' => ['dewan-guru'],
            ],
            [
                'nama' => 'Ulinuha Neviyand, S.Pd.',
                'jenis_kelamin' => 'L',
                'category_slugs' => ['dewan-guru'],
            ],
        ];

        foreach ($teachers as $data) {
            $slugs = $data['category_slugs'];
            unset($data['category_slugs']);

            $teacher = Teacher::updateOrCreate(
                ['nama' => $data['nama']],
                [
                    ...$data,
                    'user_id' => null,
                    'status' => 'aktif',
                ]
            );

            $categoryIds = collect($slugs)
                ->map(fn ($slug) => $categories[$slug] ?? null)
                ->filter()
                ->values()
                ->toArray();

            $teacher->categories()->sync($categoryIds);
        }
    }
}
