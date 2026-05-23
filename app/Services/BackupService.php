<?php

namespace App\Services;

use App\Backup\BackupEncryption;
use App\Backup\BackupFileManager;
use App\Backup\DatabaseDumper;
use App\Backup\GoogleDriveUploader;
use App\Models\BackupLog;
use App\Models\SecuritySetting;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class BackupService
{
    public function __construct(
        protected DatabaseDumper $dumper,
        protected BackupEncryption $encryption,
        protected GoogleDriveUploader $driveUploader,
        protected BackupFileManager $fileManager,
    ) {}

    /**
     * Run the full backup process.
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
            'backup_storage' => true,
            'encryption_enabled' => true,
            'google_drive_enabled' => false,
            'retention_days' => 30,
        ]);

        $backupDb = $forceFull ? true : ($backupSettings['backup_db'] ?? true);
        $backupStorage = $forceFull ? true : ($backupSettings['backup_storage'] ?? true);
        $selectedFolders = $forceFull ? [] : ($backupSettings['storage_folders'] ?? []);
        $encryptEnabled = $backupSettings['encryption_enabled'] ?? true;
        $driveEnabled = $backupSettings['google_drive_enabled'] ?? false;

        $decryptedPassphrase = '';
        if ($encryptEnabled) {
            $encryptedPass = $backupSettings['passphrase'] ?? '';
            if (empty($encryptedPass)) {
                throw new Exception('Kata sandi/passphrase enkripsi backup kosong. Silakan setel terlebih dahulu di halaman Keamanan.');
            }
            try {
                $decryptedPassphrase = Crypt::decryptString($encryptedPass);
            } catch (Exception $e) {
                throw new Exception('Gagal mendekripsi passphrase enkripsi backup: '.$e->getMessage());
            }
        }

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
                throw new Exception('Gagal membuat berkas arsip ZIP baru.');
            }

            // 2. Dump Database
            if ($backupDb) {
                $sqlFile = $tempDir.'/database_dump.sql';
                $this->dumper->dump($sqlFile);
                $zip->addFile($sqlFile, 'database_dump.sql');
            }

            // 3. Compress File Storage (selective or full)
            if ($backupStorage) {
                $storagePath = storage_path('app/public');

                if (file_exists($storagePath)) {
                    if (! empty($selectedFolders)) {
                        foreach ($selectedFolders as $folder) {
                            $folderPath = $storagePath.'/'.basename($folder);
                            if (is_dir($folderPath)) {
                                $this->fileManager->addFolderToZip($folderPath, $zip, 'storage_uploads/'.basename($folder));
                            }
                        }
                    } else {
                        $this->fileManager->addFolderToZip($storagePath, $zip, 'storage_uploads');
                    }
                } else {
                    Log::warning('Backup: Folder storage uploads public tidak ditemukan untuk dikompresi.');
                }
            }

            $zip->close();

            // 4. Encrypt or copy ZIP
            $timestamp = date('Ymd_His');
            $finalFilename = 'backup_'.$timestamp;
            $finalExtension = $encryptEnabled ? '.enc' : '.zip';
            $finalFilePath = $backupDir.'/'.$finalFilename.$finalExtension;

            if ($encryptEnabled) {
                $this->encryption->encryptFile($zipFile, $finalFilePath, $decryptedPassphrase);
            } else {
                copy($zipFile, $finalFilePath);
            }

            // 5. Upload to Google Drive (if enabled)
            $driveFileId = null;
            $driveError = null;

            if ($driveEnabled) {
                try {
                    $driveFileId = $this->driveUploader->upload($finalFilePath, $finalFilename.$finalExtension);
                } catch (Exception $e) {
                    $driveError = $e->getMessage();
                    Log::error('Backup: Gagal mengunggah ke Google Drive: '.$driveError);
                }
            }

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);
            $fileSize = file_exists($finalFilePath) ? filesize($finalFilePath) : 0;

            // 6. Cleanup temp directory
            $this->fileManager->recursiveDelete($tempDir);

            // 7. Save history log
            $newBackupLog = [
                'filename' => $finalFilename.$finalExtension,
                'type' => ($backupDb && $backupStorage) ? 'Full Backup' : ($backupDb ? 'Database Only' : 'Files Only'),
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

            return array_merge($newBackupLog, [
                'id' => $logModel->id,
                'formatted_size' => $logModel->formatted_size,
                'formatted_date' => $logModel->created_at->format('d-m-Y H:i:s'),
            ]);

        } catch (Exception $e) {
            if (isset($zip) && $zip instanceof ZipArchive) {
                @$zip->close();
            }
            if (file_exists($tempDir)) {
                $this->fileManager->recursiveDelete($tempDir);
            }

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);

            $newBackupLog = [
                'filename' => 'Gagal_'.date('Ymd_His'),
                'type' => ($backupDb && $backupStorage) ? 'Full Backup' : ($backupDb ? 'Database Only' : 'Files Only'),
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
    public function uploadToGoogleDrive(string $filePath, string $filename): string
    {
        return $this->driveUploader->upload($filePath, $filename);
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
