<?php

namespace App\Services;

use App\Models\BackupLog;
use App\Models\PpdbSetting;
use App\Models\SecuritySetting;
use Exception;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupService
{
    /**
     * Run the backup process.
     * Returns an array with results, path, size, and duration.
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

        // 1. Create temp working directory inside storage/app/backups/temp
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
                $this->dumpDatabase($sqlFile);
                $zip->addFile($sqlFile, 'database_dump.sql');
            }

            // 3. Compress File Storage (selective or full)
            if ($backupStorage) {
                $storagePath = storage_path('app/public');
                if (file_exists($storagePath)) {
                    if (! empty($selectedFolders)) {
                        // Backup only selected subdirectories
                        foreach ($selectedFolders as $folder) {
                            $folderPath = $storagePath.'/'.basename($folder);
                            if (is_dir($folderPath)) {
                                $this->addFolderToZip($folderPath, $zip, 'storage_uploads/'.basename($folder));
                            }
                        }
                    } else {
                        // Backup entire storage/app/public
                        $this->addFolderToZip($storagePath, $zip, 'storage_uploads');
                    }
                } else {
                    Log::warning('Backup: Folder storage uploads public tidak ditemukan untuk dikompresi.');
                }
            }

            $zip->close();

            // 4. Encrypt ZIP
            $timestamp = date('Ymd_His');
            $finalFilename = 'backup_'.$timestamp;
            $finalExtension = $encryptEnabled ? '.enc' : '.zip';
            $finalFilePath = $backupDir.'/'.$finalFilename.$finalExtension;

            if ($encryptEnabled) {
                $this->encryptFile($zipFile, $finalFilePath, $decryptedPassphrase);
            } else {
                copy($zipFile, $finalFilePath);
            }

            // 5. Upload to Google Drive (if enabled)
            $driveFileId = null;
            $driveError = null;
            if ($driveEnabled) {
                try {
                    $driveFileId = $this->uploadToGoogleDrive($finalFilePath, $finalFilename.$finalExtension);
                } catch (Exception $e) {
                    $driveError = $e->getMessage();
                    Log::error('Backup: Gagal mengunggah ke Google Drive: '.$driveError);
                }
            }

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);
            $fileSize = file_exists($finalFilePath) ? filesize($finalFilePath) : 0;

            // 6. Cleanup temp working directory
            $this->recursiveDelete($tempDir);

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
            $logModel = $this->addHistoryLog($newBackupLog);

            // 8. Run Retention Cleanup
            $this->cleanupOldBackups($backupSettings['retention_days'] ?? 30);

            return array_merge($newBackupLog, [
                'id' => $logModel->id,
                'formatted_size' => $logModel->formatted_size,
                'formatted_date' => $logModel->created_at->format('d-m-Y H:i:s'),
            ]);

        } catch (Exception $e) {
            if (isset($zip) && $zip instanceof ZipArchive) {
                @$zip->close();
            }
            // Ensure temp is deleted on failure
            if (file_exists($tempDir)) {
                $this->recursiveDelete($tempDir);
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
            $logModel = $this->addHistoryLog($newBackupLog);
            $newBackupLog['id'] = $logModel->id;
            $newBackupLog['formatted_size'] = '-';
            $newBackupLog['formatted_date'] = $logModel->created_at->format('d-m-Y H:i:s');

            throw $e;
        }
    }

    /**
     * Pure PHP Database dumper supporting SQLite & MySQL with efficient chunking.
     */
    protected function dumpDatabase(string $outputPath): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');
            if (file_exists($dbPath)) {
                copy($dbPath, $outputPath);
            } else {
                throw new Exception('Database SQLite file tidak ditemukan.');
            }

            return;
        }

        if ($driver !== 'mysql') {
            throw new Exception('Driver database '.$driver.' saat ini belum didukung untuk PHP Native dumper.');
        }

        // Try using mysqldump if available on system as primary fallback
        $mysqlDumpSuccess = $this->tryMysqlDump($outputPath);
        if ($mysqlDumpSuccess) {
            return;
        }

        // Run pure PHP MySQL dumper
        $this->runNativePhpDump($outputPath);
    }

    /**
     * Run mysqldump command line execution as a high-performance primary dumper.
     */
    protected function tryMysqlDump(string $outputPath): bool
    {
        if (! function_exists('exec')) {
            return false;
        }

        $host = config('database.connections.mysql.host', '127.0.0.1');
        $port = config('database.connections.mysql.port', '3306');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        // Build command safely
        // In Windows, password option shouldn't have quotes inside or spacing issues
        $passOpt = ! empty($password) ? '-p'.escapeshellarg($password) : '';
        $cmd = sprintf(
            'mysqldump -h %s --port=%s -u %s %s %s > %s 2> %s',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            $passOpt,
            escapeshellarg($database),
            escapeshellarg($outputPath),
            escapeshellarg($outputPath.'.err')
        );

        exec($cmd, $output, $returnCode);

        // Delete err file
        if (file_exists($outputPath.'.err')) {
            unlink($outputPath.'.err');
        }

        return $returnCode === 0 && file_exists($outputPath) && filesize($outputPath) > 0;
    }

    /**
     * Pure PHP MySQL dumper to write schemas and insert rows seamlessly.
     */
    protected function runNativePhpDump(string $outputPath): void
    {
        $handle = fopen($outputPath, 'w');
        if (! $handle) {
            throw new Exception('Gagal membuka file temporer untuk dumping database.');
        }

        // Headers
        fwrite($handle, "-- MAM Limpung PHP Native Database Dump\n");
        fwrite($handle, '-- Dibuat pada: '.date('Y-m-d H:i:s')."\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
        fwrite($handle, "SET NAMES utf8mb4;\n\n");

        // Get all tables
        $tablesQuery = DB::select('SHOW TABLES');
        $dbNameKey = 'Tables_in_'.config('database.connections.mysql.database');

        foreach ($tablesQuery as $tableObj) {
            $tableName = $tableObj->$dbNameKey ?? current((array) $tableObj);

            fwrite($handle, "-- -----------------------------------------------------\n");
            fwrite($handle, "-- Struktur untuk tabel: `{$tableName}`\n");
            fwrite($handle, "-- -----------------------------------------------------\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

            // Create Table Schema
            $createTableObj = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $createTableSql = $createTableObj[0]->{'Create Table'} ?? '';
            fwrite($handle, $createTableSql.";\n\n");

            // Dump data rows
            fwrite($handle, "-- Dumping data untuk tabel: `{$tableName}`\n");

            // Get first column for sorting to satisfy Laravel chunk() rule
            $firstColumn = null;
            try {
                $columnsQuery = DB::select("SHOW COLUMNS FROM `{$tableName}`");
                $firstColumn = $columnsQuery[0]->Field ?? null;
            } catch (Exception $e) {
                // Ignore fallback
            }

            $query = DB::table($tableName);
            if ($firstColumn) {
                $query->orderBy($firstColumn);
            }

            $query->chunk(250, function ($rows) use ($handle, $tableName) {
                if ($rows->isEmpty()) {
                    return;
                }

                $fields = array_keys((array) $rows->first());
                $fieldsEscaped = array_map(fn ($f) => "`{$f}`", $fields);
                $fieldsList = implode(', ', $fieldsEscaped);

                fwrite($handle, "INSERT INTO `{$tableName}` ({$fieldsList}) VALUES \n");

                $rowLines = [];
                foreach ($rows as $row) {
                    $values = [];
                    foreach ((array) $row as $val) {
                        if ($val === null) {
                            $values[] = 'NULL';
                        } else {
                            // Escape single quotes for SQL safely
                            $escaped = str_replace(['\\', "'", "\n", "\r"], ['\\\\', "\\'", '\\n', '\\r'], $val);
                            $values[] = "'{$escaped}'";
                        }
                    }
                    $rowLines[] = '('.implode(', ', $values).')';
                }

                fwrite($handle, implode(",\n", $rowLines).";\n");
            });

            fwrite($handle, "\n\n");
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
    }

    /**
     * Zip recursive folder packer.
     */
    protected function addFolderToZip(string $folderPath, ZipArchive $zip, string $zipSubdir): void
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folderPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (! $file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipSubdir.'/'.substr($filePath, strlen($folderPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    /**
     * Standard OpenSSL Encryption compatible with modern openssl CLI using PBKDF2.
     */
    public function encryptFile(string $sourcePath, string $destPath, string $password): void
    {
        $salt = openssl_random_pseudo_bytes(8);
        $iterations = 10000;
        $salted = hash_pbkdf2('sha256', $password, $salt, $iterations, 48, true);
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);

        $data = file_get_contents($sourcePath);
        if ($data === false) {
            throw new Exception('Gagal membaca berkas sumber raw zip.');
        }

        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        if ($encrypted === false) {
            throw new Exception('Gagal mengenkripsi berkas menggunakan AES-256 OpenSSL.');
        }

        // OpenSSL magic header format: Salted__ (8 bytes) + Salt (8 bytes) + Encrypted Data
        $output = 'Salted__'.$salt.$encrypted;
        if (file_put_contents($destPath, $output) === false) {
            throw new Exception('Gagal menulis berkas backup terenkripsi ke penyimpanan.');
        }
    }

    /**
     * OpenSSL Decryption.
     */
    public function decryptFile(string $sourcePath, string $destPath, string $password): void
    {
        $data = file_get_contents($sourcePath);
        if ($data === false) {
            throw new Exception('Gagal membaca berkas enkripsi sumber.');
        }

        if (substr($data, 0, 8) !== 'Salted__') {
            throw new Exception('Format berkas backup tidak valid atau tidak terenkripsi menggunakan format standard OpenSSL.');
        }

        $salt = substr($data, 8, 8);
        $encrypted = substr($data, 16);

        $iterations = 10000;
        $salted = hash_pbkdf2('sha256', $password, $salt, $iterations, 48, true);
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);

        $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) {
            throw new Exception('Sandi (Passphrase) dekripsi salah atau berkas backup terenkripsi rusak.');
        }

        if (file_put_contents($destPath, $decrypted) === false) {
            throw new Exception('Gagal menulis berkas raw zip hasil dekripsi.');
        }
    }

    /**
     * Upload an encrypted backup file to Google Drive.
     */
    public function uploadToGoogleDrive(string $filePath, string $filename): string
    {
        $credentials = $this->getServiceAccountCredentials();
        if (! $credentials) {
            throw new Exception('Kredensial Google Service Account tidak ditemukan di panel keamanan terpusat.');
        }

        $client = new GoogleClient;
        $client->setAuthConfig($credentials);
        $client->addScope(GoogleDrive::DRIVE);

        if (app()->environment('local')) {
            $client->setHttpClient(new GuzzleClient(['verify' => false]));
        }

        $driveService = new GoogleDrive($client);

        $backupSettings = SecuritySetting::getValue('backup_settings', []);
        $folderId = $backupSettings['google_drive_folder_id'] ?? null;

        $fileMetadataOpts = [
            'name' => $filename,
        ];

        if (! empty($folderId)) {
            $fileMetadataOpts['parents'] = [$folderId];
        }

        $fileMetadata = new DriveFile($fileMetadataOpts);

        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new Exception('Gagal membaca berkas backup untuk diunggah ke Google Drive.');
        }

        $file = $driveService->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'multipart',
            'fields' => 'id',
        ]);

        if (empty($file->id)) {
            throw new Exception('Gagal mengunggah berkas ke Google Drive (tidak ada ID berkas dikembalikan).');
        }

        return $file->id;
    }

    /**
     * Retrieve central Service Account Credentials.
     */
    public function getServiceAccountCredentials(): ?array
    {
        $securityCredentials = SecuritySetting::getValue('security_credentials', []);
        $encryptedJson = $securityCredentials['google_service_account_json'] ?? '';

        if (empty($encryptedJson)) {
            // Check in google sheets config too as fallback
            $gsConfig = PpdbSetting::getValue('google_sheets', []);
            $encryptedJson = $gsConfig['service_account_json'] ?? '';
        }

        if (empty($encryptedJson)) {
            return null;
        }

        try {
            $decryptedJson = Crypt::decryptString($encryptedJson);

            return json_decode($decryptedJson, true);
        } catch (Exception $e) {
            Log::error('Backup: Gagal mendekripsi kredensial Google: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Helper to add log info to BackupLog.
     */
    protected function addHistoryLog(array $logItem): BackupLog
    {
        return BackupLog::create($logItem);
    }

    /**
     * Clean old backups exceeding retention days.
     */
    public function cleanupOldBackups(int $retentionDays): int
    {
        $backupDir = storage_path('app/backups');
        if (! file_exists($backupDir)) {
            return 0;
        }

        $files = glob($backupDir.'/backup_*');
        $deletedCount = 0;
        $expiryTime = time() - ($retentionDays * 86400);

        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < $expiryTime) {
                unlink($file);
                $deletedCount++;
            }
        }

        // Clean database logs as well
        BackupLog::where('created_at', '<', now()->subDays($retentionDays))->delete();

        return $deletedCount;
    }

    /**
     * Delete directory recursively with Windows file lock fixes.
     */
    protected function recursiveDelete(string $dirPath): void
    {
        if (! file_exists($dirPath)) {
            return;
        }
        if (! is_dir($dirPath)) {
            @unlink($dirPath);

            return;
        }

        $files = [];
        $dirs = [];

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dirPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($iterator as $item) {
                $path = $item->getRealPath();
                if ($item->isDir()) {
                    $dirs[] = $path;
                } else {
                    $files[] = $path;
                }
            }
            // Free iterator handle
            unset($iterator);
        } catch (Exception $e) {
            // Ignore traversal errors and try best-effort delete
        }

        // Force GC to unlock open handles in Windows
        gc_collect_cycles();

        // Delete all files first
        foreach ($files as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }

        // Sort directories by length descending to delete leaves first
        usort($dirs, function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        // Delete directories
        foreach ($dirs as $dir) {
            if (is_dir($dir)) {
                @rmdir($dir);
            }
        }

        // Delete main folder
        if (is_dir($dirPath)) {
            @rmdir($dirPath);
        }
    }

    /**
     * Get subdirectories in public storage with their calculated sizes.
     */
    public function getStorageDirectories(): array
    {
        $storagePath = storage_path('app/public');
        if (! file_exists($storagePath)) {
            return [];
        }

        $dirs = glob($storagePath.'/*', GLOB_ONLYDIR);
        if ($dirs === false) {
            return [];
        }

        $result = [];
        foreach ($dirs as $dir) {
            $name = basename($dir);
            $size = $this->getDirSize($dir);
            $result[] = [
                'name' => $name,
                'size' => $size,
                'formatted_size' => $this->formatBytes($size),
            ];
        }

        return $result;
    }

    /**
     * Calculate directory size recursively.
     */
    protected function getDirSize(string $dir): int
    {
        $size = 0;
        if (! is_dir($dir)) {
            return 0;
        }

        try {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }
        } catch (Exception $e) {
            // Fallback to 0 if directory is inaccessible
        }

        return $size;
    }

    /**
     * Format bytes to human readable size.
     */
    protected function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = (int) floor(log($bytes, 1024));

        return round($bytes / pow(1024, $i), 2).' '.$units[$i];
    }
}
