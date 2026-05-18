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
            'target_quota' => 120,
            'registration_fee' => 150000,
            'start_date' => date('Y').'-05-01',
            'end_date' => date('Y').'-08-31',
        ]);

        // 2. Requirements Config
        PpdbSetting::setValue('requirements', [
            ['id' => 'foto', 'label' => 'Pas Foto 3x4 (Background Merah)', 'required' => true],
            ['id' => 'kk', 'label' => 'Kartu Keluarga (KK)', 'required' => true],
            ['id' => 'ijazah', 'label' => 'Ijazah / Surat Keterangan Lulus (SKL)', 'required' => true],
            ['id' => 'rapor', 'label' => 'Rapor SMP/MTs Terakhir', 'required' => false],
        ]);

        // 3. Form Dynamic Fields Schema
        PpdbSetting::setValue('form_fields', [
            [
                'id' => 'nama_wali',
                'label' => 'Nama Wali (Jika Tidak Tinggal Bersama Orang Tua)',
                'type' => 'text',
                'options' => [],
                'required' => false,
            ],
            [
                'id' => 'pekerjaan_wali',
                'label' => 'Pekerjaan Wali',
                'type' => 'text',
                'options' => [],
                'required' => false,
            ],
            [
                'id' => 'hobi_siswa',
                'label' => 'Hobi / Minat Khusus Siswa',
                'type' => 'text',
                'options' => [],
                'required' => false,
            ],
        ]);
    }
}
