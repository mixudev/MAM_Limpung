<?php

namespace Database\Seeders;

use App\Models\TeacherCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeacherCategorySeeder extends Seeder
{
    private array $categories = [
        ['name' => 'Komite Madrasah',      'description' => 'Pengawas dan mitra strategis pengembangan madrasah.'],
        ['name' => 'Kepala Madrasah',      'description' => 'Pemimpin tertinggi di lingkungan madrasah.'],
        ['name' => 'Waka Kurikulum',       'description' => 'Wakil Kepala bidang kurikulum.'],
        ['name' => 'Waka Kesiswaan',       'description' => 'Wakil Kepala bidang kesiswaan.'],
        ['name' => 'Waka Sarpras',         'description' => 'Wakil Kepala bidang sarana dan prasarana.'],
        ['name' => 'Kepala Tata Usaha',    'description' => 'Kepala urusan tata usaha dan administrasi madrasah.'],
        ['name' => 'Staf Tata Usaha',      'description' => 'Staf administrasi dan tata usaha madrasah.'],
        ['name' => 'Bendahara Madrasah',   'description' => 'Bendahara yang mengelola keuangan madrasah.'],
        ['name' => 'Bendahara BOS',        'description' => 'Bendahara khusus pengelola dana BOS.'],
        ['name' => 'Operator Madrasah',    'description' => 'Operator sistem dan data akademik madrasah.'],
        ['name' => 'Kepala Lab Komputer',  'description' => 'Penanggung jawab laboratorium komputer.'],
        ['name' => 'Kepala Lab IPA',       'description' => 'Penanggung jawab laboratorium IPA.'],
        ['name' => 'Kepala Perpustakaan',  'description' => 'Penanggung jawab perpustakaan madrasah.'],
        ['name' => 'Satpam Madrasah',      'description' => 'Petugas keamanan madrasah.'],
        ['name' => 'Wali Kelas X A',       'description' => 'Wali kelas X A.'],
        ['name' => 'Wali Kelas X B',       'description' => 'Wali kelas X B.'],
        ['name' => 'Wali Kelas XI A',      'description' => 'Wali kelas XI A.'],
        ['name' => 'Wali Kelas XI B',      'description' => 'Wali kelas XI B.'],
        ['name' => 'Wali Kelas XII A',     'description' => 'Wali kelas XII A.'],
        ['name' => 'Wali Kelas XII B',     'description' => 'Wali kelas XII B.'],
        ['name' => 'Guru BK',              'description' => 'Guru Bimbingan Konseling.'],
        ['name' => 'Dewan Guru',           'description' => 'Tenaga pendidik pengampu mata pelajaran.'],
    ];

    public function run(): void
    {
        foreach ($this->categories as $cat) {
            TeacherCategory::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                $cat
            );
        }
    }
}
