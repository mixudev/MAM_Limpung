<?php

namespace Database\Seeders;

use App\Models\PpdbSetting;
use Illuminate\Database\Seeder;

class PpdbSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. General Config
        PpdbSetting::setValue('general', [
            'is_open' => true,
            'tahun_ajaran' => (int) date('Y'),
        ]);

        // 2. Waves Config
        PpdbSetting::setValue('waves', [
            [
                'id' => 'gelombang-1',
                'name' => 'Gelombang 1',
                'start_date' => date('Y').'-01-01',
                'end_date' => date('Y').'-04-30',
            ],
            [
                'id' => 'gelombang-2',
                'name' => 'Gelombang 2',
                'start_date' => date('Y').'-05-01',
                'end_date' => date('Y').'-08-31',
            ],
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
