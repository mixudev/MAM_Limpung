<?php

namespace App\Console\Commands;

use App\Services\StorageSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StorageSyncCommand extends Command
{
    protected $signature = 'app:storage-sync {--manual : Whether triggered manually from UI}';

    protected $description = 'Sinkronisasi file storage ke Google Drive secara bertahap per-job';

    public function handle(StorageSyncService $syncService): int
    {
        $isManual = $this->option('manual');
        $source = $isManual ? 'Manual (UI)' : 'Otomatis (Scheduler)';

        $this->info("=== Storage Sync [Source: {$source}] ===");
        Log::info("StorageSync: Memulai sinkronisasi. Source: {$source}");

        try {
            $dispatched = $syncService->dispatchSyncJobs();

            if ($dispatched > 0) {
                $this->info("{$dispatched} file baru/berubah akan di-sync.");
                Log::info("StorageSync: {$dispatched} job sync telah didispatch.");
            } else {
                $this->info('Tidak ada file baru. Semua file sudah tersinkronisasi.');
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Storage Sync gagal: '.$e->getMessage());
            Log::error('StorageSync Error: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
