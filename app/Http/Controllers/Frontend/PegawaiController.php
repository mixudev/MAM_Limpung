<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = collect([
            // ================= GURU =================
            (object)[
                'id' => 1,
                'nama' => 'Ahmad Fauzi, S.Pd',
                'nip' => '198706102019031001',
                'jabatan' => 'Kepala Sekolah',
                'mapel' => 'Matematika',
                'bidang' => null,
                'tipe' => 'guru',
            ],
            (object)[
                'id' => 2,
                'nama' => 'Siti Aminah, S.Pd',
                'nip' => '198905222020122002',
                'jabatan' => 'Guru Bahasa Indonesia',
                'mapel' => 'Bahasa Indonesia',
                'bidang' => null,
                'tipe' => 'guru',
            ],
            (object)[
                'id' => 3,
                'nama' => 'Muhammad Rizki, M.Pd',
                'nip' => '198402112018011003',
                'jabatan' => 'Guru Fisika',
                'mapel' => 'Fisika',
                'bidang' => null,
                'tipe' => 'guru',
            ],
            (object)[
                'id' => 4,
                'nama' => 'Nur Aisyah, S.Pd',
                'nip' => '199001152021032004',
                'jabatan' => 'Guru Bahasa Inggris',
                'mapel' => 'Bahasa Inggris',
                'bidang' => null,
                'tipe' => 'guru',
            ],
            (object)[
                'id' => 5,
                'nama' => 'Hendra Saputra, S.Kom',
                'nip' => '198812102019061005',
                'jabatan' => 'Guru Informatika',
                'mapel' => 'Informatika',
                'bidang' => null,
                'tipe' => 'guru',
            ],
            (object)[
                'id' => 6,
                'nama' => 'Dewi Lestari, M.Pd',
                'nip' => '198703192017041006',
                'jabatan' => 'Guru Biologi',
                'mapel' => 'Biologi',
                'bidang' => null,
                'tipe' => 'guru',
            ],
            (object)[
                'id' => 7,
                'nama' => 'Agus Pratama, S.Pd',
                'nip' => '199102012022071007',
                'jabatan' => 'Guru Sejarah',
                'mapel' => 'Sejarah',
                'bidang' => null,
                'tipe' => 'guru',
            ],
            (object)[
                'id' => 8,
                'nama' => 'Rina Wulandari, S.Pd',
                'nip' => '199203102021082008',
                'jabatan' => 'Guru Geografi',
                'mapel' => 'Geografi',
                'bidang' => null,
                'tipe' => 'guru',
            ],
            (object)[
                'id' => 9,
                'nama' => 'Fajar Nugroho, M.Pd',
                'nip' => '198601212016091009',
                'jabatan' => 'Guru Ekonomi',
                'mapel' => 'Ekonomi',
                'bidang' => null,
                'tipe' => 'guru',
            ],
            (object)[
                'id' => 10,
                'nama' => 'Lina Marlina, S.Pd',
                'nip' => '199011302020102010',
                'jabatan' => 'Guru Sosiologi',
                'mapel' => 'Sosiologi',
                'bidang' => null,
                'tipe' => 'guru',
            ],
            (object)[
                'id' => 11,
                'nama' => 'Rahmat Hidayat, S.Ag',
                'nip' => '198505172015111011',
                'jabatan' => 'Guru Pendidikan Agama',
                'mapel' => 'PAI',
                'bidang' => null,
                'tipe' => 'guru',
            ],
            (object)[
                'id' => 12,
                'nama' => 'Yuni Kartika, S.Pd',
                'nip' => '199104212022121012',
                'jabatan' => 'Guru Seni Budaya',
                'mapel' => 'Seni Budaya',
                'bidang' => null,
                'tipe' => 'guru',
            ],

            // ================= STAF TU =================
            (object)[
                'id' => 13,
                'nama' => 'Budi Santoso',
                'nip' => '197912122010011013',
                'jabatan' => 'Kepala Tata Usaha',
                'mapel' => null,
                'bidang' => 'Administrasi',
                'tipe' => 'staf',
            ],
            (object)[
                'id' => 14,
                'nama' => 'Sri Wahyuni',
                'nip' => '198308092012022014',
                'jabatan' => 'Staf Keuangan',
                'mapel' => null,
                'bidang' => 'Keuangan',
                'tipe' => 'staf',
            ],
            (object)[
                'id' => 15,
                'nama' => 'Andi Kurniawan',
                'nip' => '198912172015031015',
                'jabatan' => 'Staf Kesiswaan',
                'mapel' => null,
                'bidang' => 'Kesiswaan',
                'tipe' => 'staf',
            ],
            (object)[
                'id' => 16,
                'nama' => 'Maya Puspitasari',
                'nip' => '199203142018041016',
                'jabatan' => 'Staf Umum',
                'mapel' => null,
                'bidang' => 'Umum',
                'tipe' => 'staf',
            ],
        ]);

        return view('front.data_pegawai.index', compact('pegawai'));
    }

    public function show($id)
    {
        // dummy detail page
        abort_unless($id, 404);
        return "Detail pegawai ID: $id";
    }
}