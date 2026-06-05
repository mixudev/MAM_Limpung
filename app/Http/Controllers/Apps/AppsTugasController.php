<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppsTugasController extends Controller
{
    /**
     * Display school tasks list for students (mock data)
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa')) {
            return redirect()->route('dashboard');
        }

        // Mock tasks data
        $tasks = [
            [
                'id' => 1,
                'mapel' => 'Matematika Peminatan',
                'judul' => 'Turunan Fungsi Trigonometri',
                'deskripsi' => 'Kerjakan Latihan Soal Bab 3 halaman 45 nomor 1-10 di buku tulis. Foto jawaban lalu upload ke portal.',
                'deadline' => now()->addDays(2)->format('d M Y, 23:59'),
                'guru' => 'Dra. Siti Rahmah',
                'status' => 'Belum Selesai',
                'priority' => 'Tinggi',
            ],
            [
                'id' => 2,
                'mapel' => 'Bahasa Inggris',
                'judul' => 'Analytical Exposition Essay',
                'deskripsi' => 'Buat sebuah teks analytical exposition dengan topik bebas (minimal 3 paragraf). Tulis di dokumen word/PDF.',
                'deadline' => now()->addDays(5)->format('d M Y, 23:59'),
                'guru' => 'Budi Santoso, S.Pd.',
                'status' => 'Belum Selesai',
                'priority' => 'Sedang',
            ],
            [
                'id' => 3,
                'mapel' => 'Fisika',
                'judul' => 'Laporan Praktikum Gelombang',
                'deskripsi' => 'Susun laporan praktikum gelombang stasioner kelompok kemarin lengkap dengan tabel hasil dan analisis data.',
                'deadline' => now()->subDays(1)->format('d M Y, 14:00'),
                'guru' => 'Hendra Wijaya, M.Si.',
                'status' => 'Selesai',
                'priority' => 'Rendah',
            ],
        ];

        return view('mobile_apps.tugas.index', compact('tasks'));
    }
}
