<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\PpdbSetting;
use App\Models\RegistrationWave;
use Illuminate\Database\Seeder;

class PpdbSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Academic Year & Waves
        $year = (int) date('Y');
        $academicYear = AcademicYear::firstOrCreate(
            ['year' => $year],
            [
                'name' => $year.'/'.($year + 1),
                'is_active' => true,
            ]
        );

        if ($academicYear->waves()->count() === 0) {
            RegistrationWave::insert([
                [
                    'academic_year_id' => $academicYear->id,
                    'slug' => 'gelombang-1',
                    'name' => 'Gelombang 1',
                    'start_date' => $year.'-01-01',
                    'end_date' => $year.'-04-30',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'academic_year_id' => $academicYear->id,
                    'slug' => 'gelombang-2',
                    'name' => 'Gelombang 2',
                    'start_date' => $year.'-05-01',
                    'end_date' => $year.'-08-31',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // 2. General Config (legacy — only is_open, no tahun_ajaran)
        PpdbSetting::setValue('general', [
            'is_open' => true,
        ]);

        // 3. Requirements Config
        PpdbSetting::setValue('requirements', [
            ['id' => 'foto', 'label' => 'Pas Foto 3x4 (Background Merah)', 'required' => true, 'is_active' => true],
            ['id' => 'kk', 'label' => 'Kartu Keluarga (KK)', 'required' => true, 'is_active' => true],
            ['id' => 'ijazah', 'label' => 'Ijazah / Surat Keterangan Lulus (SKL)', 'required' => true, 'is_active' => true],
            ['id' => 'rapor', 'label' => 'Rapor SMP/MTs Terakhir', 'required' => false, 'is_active' => true],
        ]);

        // 4. Form Dynamic Fields Schema
        PpdbSetting::setValue('form_fields', [
            [
                'id' => 'nama_wali',
                'label' => 'Nama Wali (Jika Tidak Tinggal Bersama Orang Tua)',
                'type' => 'text',
                'options' => [],
                'required' => false,
                'is_active' => true,
            ],
            [
                'id' => 'pekerjaan_wali',
                'label' => 'Pekerjaan Wali',
                'type' => 'text',
                'options' => [],
                'required' => false,
                'is_active' => true,
            ],
            [
                'id' => 'hobi_siswa',
                'label' => 'Hobi / Minat Khusus Siswa',
                'type' => 'text',
                'options' => [],
                'required' => false,
                'is_active' => true,
            ],
        ]);
    }
}
