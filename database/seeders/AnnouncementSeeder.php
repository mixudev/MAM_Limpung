<?php

namespace Database\Seeders;

use App\Models\AnnounceAd;
use App\Models\AnnounceAlert;
use App\Models\AnnounceText;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Running Text Announcements
        $runningTexts = [
            [
                'title' => 'PPDB 2026/2027 Dibuka',
                'content' => 'Penerimaan Peserta Didik Baru (PPDB) MA Muhammadiyah Limpung Tahun Pelajaran 2026/2027 Resmi Dibuka! Hubungi Panitia di +62 812-3456-789.',
                'is_active' => true,
            ],
            [
                'title' => 'Ujian Akhir Semester',
                'content' => 'Diberitahukan kepada seluruh siswa kelas X, XI, dan XII bahwa Ujian Akhir Semester Genap akan dilaksanakan mulai tanggal 1 Juni 2026.',
                'is_active' => true,
            ],
        ];

        foreach ($runningTexts as $text) {
            AnnounceText::updateOrCreate(
                ['title' => $text['title']],
                [
                    'content' => $text['content'],
                    'is_active' => $text['is_active'],
                ]
            );
        }

        // 2. Alert Popups (Modal Dialogs)
        $alerts = [
            [
                'title' => 'Selamat Datang di PPDB Online MAM Limpung',
                'content' => '<p>Pendaftaran gelombang kedua saat ini sedang berlangsung hingga 31 Agustus 2026. Segera daftarkan diri Anda dan raih beasiswa prestasi maupun beasiswa kader Muhammadiyah!</p>',
                'image' => null,
                'action_url' => '/ppdb/daftar',
                'action_text' => 'Daftar Sekarang',
                'popup_size' => 'md',
                'display_frequency' => 'once_per_session',
                'target_page' => 'home',
                'is_active' => true,
                'start_date' => now()->subDay(),
                'end_date' => now()->addMonth(),
            ],
        ];

        foreach ($alerts as $alert) {
            AnnounceAlert::updateOrCreate(
                ['title' => $alert['title']],
                [
                    'content' => $alert['content'],
                    'image' => $alert['image'],
                    'action_url' => $alert['action_url'],
                    'action_text' => $alert['action_text'],
                    'popup_size' => $alert['popup_size'],
                    'display_frequency' => $alert['display_frequency'],
                    'target_page' => $alert['target_page'],
                    'is_active' => $alert['is_active'],
                    'start_date' => $alert['start_date'],
                    'end_date' => $alert['end_date'],
                ]
            );
        }

        // 3. Banner Ads (Horizontal Ads)
        $imagePath = 'announcements/ads/saluran_whatsapp.png';
        if (! Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->put($imagePath, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='));
        }

        $ads = [
            [
                'title' => 'Ikuti Update Terbaru di WhatsApp Channel',
                'description' => 'Klik tombol Join Channel untuk bergabung dengan channel whatsapp kami untuk mendapatkan info terbaru seputar MAM Limpung.',
                'image' => $imagePath,
                'action_url' => 'https://whatsapp.com/channel/0029VbCazBu2ER6aqGDc703u',
                'action_text' => 'Join Channel',
                'is_active' => true,
                'start_date' => now()->subDay(),
                'end_date' => now()->addMonth(),
            ],
        ];

        foreach ($ads as $ad) {
            AnnounceAd::updateOrCreate(
                ['title' => $ad['title']],
                [
                    'description' => $ad['description'],
                    'image' => $ad['image'],
                    'action_url' => $ad['action_url'],
                    'action_text' => $ad['action_text'],
                    'is_active' => $ad['is_active'],
                    'start_date' => $ad['start_date'],
                    'end_date' => $ad['end_date'],
                ]
            );
        }
    }
}
