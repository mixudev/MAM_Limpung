<?php

namespace App\Services;

use App\Backup\GoogleDriveUploader;
use App\Jobs\SyncStorageFileJob;
use App\Models\FileSyncLog;
use App\Models\SecuritySetting;
use Exception;
use Illuminate\Support\Facades\Log;

class StorageSyncService
{
    public function __construct(
        protected GoogleDriveUploader $driveUploader,
    ) {}

    /**
     * Scan storage/app/public and dispatch sync jobs for new/changed files.
     * Returns count of dispatched jobs.
     */
    public function dispatchSyncJobs(): int
    {
        $storagePath = storage_path('app/public');

        if (! file_exists($storagePath)) {
            Log::warning('StorageSync: Folder public storage tidak ditemukan.');

            return 0;
        }

        $files = $this->scanFiles($storagePath);
        $dispatched = 0;
        $basePath = $storagePath.DIRECTORY_SEPARATOR;

        foreach ($files as $filePath) {
            $relativePath = str_replace('\\', '/', substr($filePath, strlen($basePath)));
            $fileHash = md5_file($filePath);
            $fileSize = filesize($filePath);

            $existing = FileSyncLog::where('file_path', $relativePath)->first();

            if ($existing && $existing->file_hash === $fileHash && $existing->file_size === $fileSize && $existing->sync_status === 'synced') {
                continue;
            }

            if ($existing) {
                $existing->update([
                    'file_hash' => $fileHash,
                    'file_size' => $fileSize,
                    'sync_status' => 'pending',
                    'error_message' => null,
                ]);
            } else {
                FileSyncLog::create([
                    'file_path' => $relativePath,
                    'file_hash' => $fileHash,
                    'file_size' => $fileSize,
                    'sync_status' => 'pending',
                ]);
            }

            SyncStorageFileJob::dispatch($relativePath);
            $dispatched++;
        }

        $this->markDeletedFilesAsRemoved($files);

        return $dispatched;
    }

    /**
     * Sync a single file to Google Drive.
     */
    public function syncFile(string $relativePath): void
    {
        if (empty($relativePath)) {
            return;
        }

        $fullPath = storage_path('app/public/'.$relativePath);

        if (! file_exists($fullPath) || ! is_file($fullPath)) {
            FileSyncLog::where('file_path', $relativePath)->update([
                'sync_status' => 'removed',
                'error_message' => 'File tidak ditemukan di penyimpanan lokal',
            ]);

            return;
        }

        $log = FileSyncLog::where('file_path', $relativePath)->first();
        if (! $log) {
            return;
        }

        try {
            $log->update(['sync_status' => 'syncing']);

            $normalized = str_replace('\\', '/', $relativePath);
            $dirName = dirname($normalized);
            if ($dirName === '.') {
                $dirName = '';
            }
            $rootFolderId = $this->driveUploader->getOrCreateRootFolder();
            $syncFolder = 'storage'.($dirName ? '/'.$dirName : '');
            $folderId = $this->driveUploader->ensureFolderPath($syncFolder, $rootFolderId);

            $baseName = basename($relativePath);
            $driveFileId = $this->driveUploader->upload($fullPath, $baseName, $folderId);

            $log->update([
                'drive_file_id' => $driveFileId,
                'sync_status' => 'synced',
                'synced_at' => now(),
                'error_message' => null,
            ]);

            Log::info("StorageSync: File {$relativePath} berhasil di-sync ke Google Drive (ID: {$driveFileId})");
        } catch (Exception $e) {
            $log->update([
                'sync_status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error("StorageSync: Gagal sync {$relativePath}: {$e->getMessage()}");
        }
    }

    /**
     * Get sync progress stats.
     *
     * @return array<string, mixed>
     */
    public function getSyncProgress(): array
    {
        $total = FileSyncLog::count();
        $synced = FileSyncLog::where('sync_status', 'synced')->count();
        $pending = FileSyncLog::where('sync_status', 'pending')->count();
        $failed = FileSyncLog::where('sync_status', 'failed')->count();
        $running = $pending > 0;

        $settings = SecuritySetting::getValue('storage_sync_settings', ['enabled' => false]);

        return [
            'success' => true,
            'running' => $running,
            'processed' => $synced,
            'total' => $total,
            'pending' => $pending,
            'failed' => $failed,
            'enabled' => $settings['enabled'] ?? false,
            'percent' => $total > 0 ? round(($synced / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Get recent sync logs for UI display.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getRecentSyncs(int $limit = 20): array
    {
        return FileSyncLog::orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn (FileSyncLog $log) => $this->formatSyncLog($log))
            ->toArray();
    }

    /**
     * Get paginated sync logs for the professional UI.
     *
     * @return array<string, mixed>
     */
    public function getSyncLogsPaginated(int $page = 1, int $perPage = 15): array
    {
        $query = FileSyncLog::orderBy('updated_at', 'desc');

        $total = $query->count();
        $lastPage = max(1, (int) ceil($total / $perPage));
        $page = min(max($page, 1), $lastPage);

        $logs = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(fn (FileSyncLog $log) => $this->formatSyncLog($log))
            ->toArray();

        return [
            'logs' => $logs,
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => $lastPage,
            'has_prev' => $page > 1,
            'has_next' => $page < $lastPage,
        ];
    }

    /**
     * Format a FileSyncLog model for JSON response.
     *
     * @return array<string, mixed>
     */
    private function formatSyncLog(FileSyncLog $log): array
    {
        return [
            'id' => $log->id,
            'file_path' => $log->file_path,
            'file_size' => $log->file_size,
            'formatted_size' => $log->formatted_size,
            'sync_status' => $log->sync_status,
            'synced_at' => $log->synced_at?->format('d-m-Y H:i:s'),
            'updated_at' => $log->updated_at->format('d-m-Y H:i:s'),
            'error_message' => $log->error_message,
        ];
    }

    /**
     * Scan a directory recursively for all files.
     *
     * @return array<int, string>
     */
    private function scanFiles(string $dir): array
    {
        $files = [];

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $files[] = $file->getRealPath();
                }
            }
        } catch (Exception $e) {
            Log::error('StorageSync: Gagal scan direktori: '.$e->getMessage());
        }

        return $files;
    }

    /**
     * Ensure the storage_sync folder exists in Drive and return its info.
     *
     * @return array{id: string, path: string}
     */
    public function ensureStorageFolder(): array
    {
        $rootFolderId = $this->driveUploader->getOrCreateRootFolder();
        $folderId = $this->driveUploader->ensureFolderPath('storage', $rootFolderId);

        return [
            'id' => $folderId,
            'path' => 'Drive/storage',
        ];
    }

    /**
     * Mark files in log as 'removed' if they no longer exist locally.
     */
    private function markDeletedFilesAsRemoved(array $currentFiles): void
    {
        $storagePath = storage_path('app/public');
        $currentRelative = [];

        foreach ($currentFiles as $fullPath) {
            $currentRelative[] = str_replace('\\', '/', substr($fullPath, strlen($storagePath) + 1));
        }

        FileSyncLog::where('sync_status', 'synced')
            ->whereNotIn('file_path', $currentRelative)
            ->chunk(100, function ($logs) {
                foreach ($logs as $log) {
                    $log->update(['sync_status' => 'removed']);
                }
            });
    }
}
