<?php

namespace App\Backup;

use App\Models\BackupLog;
use Exception;
use ZipArchive;

class BackupFileManager
{
    /**
     * Recursively add all files in a folder to a ZipArchive under the given subdirectory path.
     */
    public function addFolderToZip(string $folderPath, ZipArchive $zip, string $zipSubdir): void
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folderPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (! $file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipSubdir.'/'.substr($filePath, strlen($folderPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    /**
     * Delete a directory and all its contents (Windows-safe: handles locked handles).
     */
    public function recursiveDelete(string $dirPath): void
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
            unset($iterator);
        } catch (Exception) {
            // Best-effort traversal; continue with what we collected
        }

        // Force GC to release open file handles on Windows
        gc_collect_cycles();

        foreach ($files as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }

        // Delete leaf directories first (sorted by path length descending)
        usort($dirs, fn ($a, $b) => strlen($b) - strlen($a));

        foreach ($dirs as $dir) {
            if (is_dir($dir)) {
                @rmdir($dir);
            }
        }

        if (is_dir($dirPath)) {
            @rmdir($dirPath);
        }
    }

    /**
     * Get subdirectories inside storage/app/public with their human-readable sizes.
     *
     * @return array<int, array{name: string, size: int, formatted_size: string}>
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
     * Delete expired backup files from disk and database logs.
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

        // Remove database log entries beyond retention window
        BackupLog::where('created_at', '<', now()->subDays($retentionDays))->delete();

        return $deletedCount;
    }

    /**
     * Calculate the total size of a directory recursively.
     */
    private function getDirSize(string $dir): int
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
        } catch (Exception) {
            // Return 0 if directory is inaccessible
        }

        return $size;
    }

    /**
     * Format a byte count to a human-readable string (e.g. "4.2 MB").
     */
    public function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = (int) floor(log($bytes, 1024));

        return round($bytes / pow(1024, $i), 2).' '.$units[$i];
    }
}
