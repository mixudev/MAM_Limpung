<?php

namespace App\Services;

use App\Backup\BackupEncryption;
use App\Backup\BackupFileManager;
use App\Backup\DatabaseDumper;
use App\Backup\GoogleDriveUploader;
use App\Models\BackupLog;
use App\Models\SecuritySetting;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class BackupService
{
    private const PROGRESS_CACHE_KEY = 'backup_progress';

    public function __construct(
        protected DatabaseDumper $dumper,
        protected BackupEncryption $encryption,
        protected GoogleDriveUploader $driveUploader,
        protected BackupFileManager $fileManager,
    ) {}

    /**
     * Update progress status in cache for UI polling.
     */
    private function setProgress(string $step, ?int $percent = null, ?string $detail = null): void
    {
        $data = [
            'step' => $step,
            'percent' => $percent ?? 0,
            'detail' => $detail,
            'updated_at' => time(),
        ];
        Cache::put(self::PROGRESS_CACHE_KEY, $data, 600);
    }

    /**
     * Get current backup progress for UI polling.
     *
     * @return array<string, mixed>
     */
    public function getProgress(): array
    {
        return Cache::get(self::PROGRESS_CACHE_KEY, [
            'step' => 'idle',
            'percent' => 0,
            'detail' => null,
            'updated_at' => time(),
        ]);
    }

    /**
     * Clear progress cache.
     */
    public function clearProgress(): void
    {
        Cache::forget(self::PROGRESS_CACHE_KEY);
    }

    /**
     * Run the full backup process with progress tracking.
     * Returns an array with results, path, size, and duration.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function runBackup(bool $forceFull = false): array
    {
        $startTime = microtime(true);
        $backupSettings = SecuritySetting::getValue('backup_settings', [
            'enabled' => false,
            'backup_db' => true,
            'encryption_enabled' => true,
            'google_drive_enabled' => false,
            'retention_days' => 30,
        ]);

        $backupDb = $forceFull ? true : ($backupSettings['backup_db'] ?? true);
        $encryptEnabled = $backupSettings['encryption_enabled'] ?? true;
        $driveEnabled = $backupSettings['google_drive_enabled'] ?? false;

        $decryptedPassphrase = '';
        if ($encryptEnabled) {
            $encryptionKey = config('backup.encryption_key');
            if (empty($encryptionKey)) {
                $this->setProgress('error', 0, 'BACKUP_ENCRYPTION_KEY belum diisi');
                throw new Exception('BACKUP_ENCRYPTION_KEY belum diisi di file .env. Isi terlebih dahulu atau nonaktifkan enkripsi backup.');
            }
            $decryptedPassphrase = $encryptionKey;
        }

        $this->setProgress('memulai', 1, 'Memeriksa konfigurasi & membuat direktori kerja...');

        // 1. Create temp working directory
        $backupDir = storage_path('app/backups');
        $tempDir = $backupDir.'/temp_'.time();

        if (! file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $sqlFile = null;
        $zipFile = $tempDir.'/backup_raw.zip';

        try {
            $zip = new ZipArchive;

            if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                $this->setProgress('error', 0, 'Gagal membuat file ZIP');
                throw new Exception('Gagal membuat berkas arsip ZIP baru.');
            }

            // 2. Dump Database
            if ($backupDb) {
                $this->setProgress('database', 10, 'Mendump database MySQL...');
                $sqlFile = $tempDir.'/database_dump.sql';
                $this->dumper->dump($sqlFile);
                $zip->addFile($sqlFile, 'database_dump.sql');
                $this->setProgress('database', 20, 'Database berhasil didump');
            }

            $zip->close();
            $this->setProgress('compressing', 70, 'Arsip ZIP berhasil dibuat');

            // 4. Encrypt or copy ZIP (hindari copy — rename langsung)
            $timestamp = date('Ymd_His');
            $finalFilename = 'backup_'.$timestamp;
            $finalExtension = $encryptEnabled ? '.enc' : '.zip';
            $finalFilePath = $backupDir.'/'.$finalFilename.$finalExtension;

            if ($encryptEnabled) {
                $this->setProgress('encrypting', 75, 'Mengenkripsi file backup dengan AES-256...');
                $this->encryption->encryptFile($zipFile, $finalFilePath, $decryptedPassphrase);
                $this->setProgress('encrypting', 85, 'Enkripsi selesai');
                @unlink($zipFile);
            } else {
                // Rename lebih cepat daripada copy + delete
                rename($zipFile, $finalFilePath);
                $this->setProgress('finalizing', 85, 'File backup siap');
            }

            // 5. Upload to Google Drive (if enabled) — dengan progress
            $driveFileId = null;
            $driveError = null;

            if ($driveEnabled) {
                $this->setProgress('drive', 87, 'Mengunggah ke Google Drive...');
                try {
                    $driveFileId = $this->driveUploader->upload($finalFilePath, $finalFilename.$finalExtension);
                    $this->setProgress('drive', 95, 'Upload Google Drive berhasil');
                } catch (Exception $e) {
                    $driveError = $e->getMessage();
                    Log::error('Backup: Gagal mengunggah ke Google Drive: '.$driveError);
                    $this->setProgress('drive', 95, 'Upload Google Drive gagal: '.$driveError);
                }
            }

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);
            $fileSize = file_exists($finalFilePath) ? filesize($finalFilePath) : 0;

            // 6. Cleanup temp directory
            if (is_dir($tempDir)) {
                $this->fileManager->recursiveDelete($tempDir);
            }

            // 7. Save history log
            $newBackupLog = [
                'filename' => $finalFilename.$finalExtension,
                'type' => $backupDb ? 'Database Only' : 'Unknown',
                'size' => $fileSize,
                'encrypted' => $encryptEnabled,
                'drive_uploaded' => ! empty($driveFileId),
                'drive_file_id' => $driveFileId,
                'drive_error' => $driveError,
                'created_at' => date('Y-m-d H:i:s'),
                'duration' => $duration,
                'status' => 'success',
            ];
            $logModel = BackupLog::create($newBackupLog);

            // 8. Run retention cleanup
            $this->fileManager->cleanupOldBackups($backupSettings['retention_days'] ?? 30);

            $this->setProgress('selesai', 100, 'Backup selesai!');

            return array_merge($newBackupLog, [
                'id' => $logModel->id,
                'formatted_size' => $logModel->formatted_size,
                'formatted_date' => $logModel->created_at->format('d-m-Y H:i:s'),
            ]);

        } catch (Exception $e) {
            if (isset($zip) && $zip instanceof ZipArchive) {
                @$zip->close();
            }
            if (is_dir($tempDir)) {
                $this->fileManager->recursiveDelete($tempDir);
            }

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);

            $newBackupLog = [
                'filename' => 'Gagal_'.date('Ymd_His'),
                'type' => $backupDb ? 'Database Only' : 'Unknown',
                'size' => 0,
                'encrypted' => $encryptEnabled,
                'drive_uploaded' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'duration' => $duration,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ];
            $logModel = BackupLog::create($newBackupLog);
            $newBackupLog['id'] = $logModel->id;
            $newBackupLog['formatted_size'] = '-';
            $newBackupLog['formatted_date'] = $logModel->created_at->format('d-m-Y H:i:s');

            $this->setProgress('error', 0, $e->getMessage());

            throw $e;
        }
    }

    /**
     * Encrypt a file. Proxies to BackupEncryption for external callers.
     */
    public function encryptFile(string $sourcePath, string $destPath, string $password): void
    {
        $this->encryption->encryptFile($sourcePath, $destPath, $password);
    }

    /**
     * Decrypt a file. Proxies to BackupEncryption for external callers.
     */
    public function decryptFile(string $sourcePath, string $destPath, string $password): void
    {
        $this->encryption->decryptFile($sourcePath, $destPath, $password);
    }

    /**
     * Upload a file to Google Drive. Proxies to GoogleDriveUploader for external callers.
     */
    public function uploadToGoogleDrive(string $filePath, string $filename, ?string $parentFolderId = null): string
    {
        return $this->driveUploader->upload($filePath, $filename, $parentFolderId);
    }

    /**
     * Retrieve central Service Account credentials. Proxies to GoogleDriveUploader.
     *
     * @return array<string, mixed>|null
     */
    public function getServiceAccountCredentials(): ?array
    {
        return $this->driveUploader->getServiceAccountCredentials();
    }

    /**
     * Get subdirectories in public storage with their sizes. Proxies to BackupFileManager.
     *
     * @return array<int, array{name: string, size: int, formatted_size: string}>
     */
    public function getStorageDirectories(): array
    {
        return $this->fileManager->getStorageDirectories();
    }

    /**
     * Clean old backup files and logs. Proxies to BackupFileManager.
     */
    public function cleanupOldBackups(int $retentionDays): int
    {
        return $this->fileManager->cleanupOldBackups($retentionDays);
    }
}
