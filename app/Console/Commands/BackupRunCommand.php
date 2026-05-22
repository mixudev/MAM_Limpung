<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackupRunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-run {--manual : Whether this is triggered manually by user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Melakukan backup basis data dan file unggahan secara terkompresi, terenkripsi aman, dan diunggah ke Google Drive.';

    /**
     * Execute the console command.
     */
    public function handle(BackupService $backupService): int
    {
        $isManual = $this->option('manual');
        $triggerSource = $isManual ? 'Manual (UI/Console)' : 'Otomatis (Scheduler)';

        $this->info("=== Memulai Proses Backup [Source: {$triggerSource}] ===");
        Log::info("Backup: Memulai proses backup. Source: {$triggerSource}");

        try {
            $result = $backupService->runBackup();
            
            $sizeInMb = round($result['size'] / (1024 * 1024), 2);
            $msg = "Backup sukses dibuat: {$result['filename']} | Ukuran: {$sizeInMb} MB | Durasi: {$result['duration']} detik.";
            
            $this->info($msg);
            if ($result['encrypted']) {
                $this->info("Status Keamanan: Terenkripsi AES-256 (OpenSSL kompatibel)");
            }
            if ($result['drive_uploaded']) {
                $this->info("Google Drive: Berhasil diunggah (ID Berkas: {$result['drive_file_id']})");
            } else {
                $backupSettings = \App\Models\PpdbSetting::getValue('backup_settings', []);
                if (!empty($backupSettings['google_drive_enabled'])) {
                    $this->warn("Google Drive: Gagal diunggah! Eror: " . ($result['drive_error'] ?? 'kredensial salah'));
                }
            }

            Log::info("Backup: " . $msg);
            return Command::SUCCESS;

        } catch (Exception $e) {
            $errMsg = "Backup gagal dijalankan: " . $e->getMessage();
            $this->error($errMsg);
            Log::error("Backup Eror: " . $errMsg);
            return Command::FAILURE;
        }
    }
}
