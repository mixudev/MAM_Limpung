<?php

namespace App\Http\Controllers\Dashboard\Backup;

use App\Http\Controllers\Controller;
use App\Models\BackupLog;
use App\Models\FileSyncLog;
use App\Models\SecuritySetting;
use App\Services\BackupService;
use App\Services\StorageSyncService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    /**
     * Show the Backup management page.
     */
    public function index(BackupService $backupService): View
    {
        $defaults = [
            'enabled' => false,
            'schedule' => 'daily',
            'cron_expression' => '0 0 * * *',
            'backup_db' => true,
            'encryption_enabled' => true,
            'google_drive_enabled' => false,
            'google_drive_folder_id' => '',
            'retention_days' => 30,
        ];
        $backupSettings = array_merge($defaults, SecuritySetting::getValue('backup_settings', []));

        // Encryption key comes from .env only — never from DB
        $hasEncryptionKey = ! empty(config('backup.encryption_key'));

        $securityCredentials = SecuritySetting::getValue('security_credentials', []);
        $hasServiceAccount = ! empty($securityCredentials['google_service_account_json'] ?? '');
        $hasOAuth2 = false;
        if (! empty($securityCredentials['google_oauth2_credentials'] ?? '')) {
            try {
                $oauth2Data = json_decode(Crypt::decryptString($securityCredentials['google_oauth2_credentials']), true);
                $hasOAuth2 = ! empty($oauth2Data['refresh_token']);
            } catch (Exception) {
                // Silently ignore
            }
        }
        $hasGoogleCredentials = $hasServiceAccount || $hasOAuth2;

        $backupHistory = BackupLog::orderBy('created_at', 'desc')->get();

        $syncSettings = array_merge(['enabled' => false], SecuritySetting::getValue('storage_sync_settings', []));

        return view('dashboard.admin.backup.index', [
            'backupSettings' => $backupSettings,
            'hasEncryptionKey' => $hasEncryptionKey,
            'hasGoogleCredentials' => $hasGoogleCredentials,
            'backupHistory' => $backupHistory,
            'syncSettings' => $syncSettings,
        ]);
    }

    /**
     * Update backup configuration settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'schedule' => ['required', 'string', 'in:daily,weekly,monthly,custom'],
            'cron_expression' => ['nullable', 'string'],
            'retention_days' => ['required', 'integer', 'min:1', 'max:365'],
            'google_drive_folder_id' => ['nullable', 'string'],
        ]);

        $config = [
            'enabled' => $request->has('enabled'),
            'schedule' => $request->input('schedule'),
            'cron_expression' => $request->input('cron_expression', '0 0 * * *'),
            'backup_db' => $request->has('backup_db'),
            'encryption_enabled' => $request->has('encryption_enabled'),
            'google_drive_enabled' => $request->has('google_drive_enabled'),
            'google_drive_folder_id' => $request->input('google_drive_folder_id'),
            'retention_days' => (int) $request->input('retention_days', 30),
        ];

        if ($config['encryption_enabled'] && empty(config('backup.encryption_key'))) {
            return back()->withErrors(['error' => 'Isi BACKUP_ENCRYPTION_KEY di file .env terlebih dahulu sebelum mengaktifkan enkripsi backup.'])->withInput();
        }

        SecuritySetting::setValue('backup_settings', $config);

        return back()->with('success', 'Konfigurasi backup berhasil diperbarui!');
    }

    /**
     * Run manual backup — fokus pada database saja.
     * Storage sync dijalankan terpisah via background job.
     */
    public function runBackup(BackupService $backupService, StorageSyncService $syncService): JsonResponse
    {
        set_time_limit(0);
        ignore_user_abort(true);

        while (ob_get_level()) {
            ob_end_clean();
        }

        try {
            $result = $backupService->runBackup(false);

            // Setelah backup DB, trigger storage sync jobs (background)
            $syncSettings = SecuritySetting::getValue('storage_sync_settings', ['enabled' => false]);
            $syncJobCount = 0;
            if (! empty($syncSettings['enabled'])) {
                $syncJobCount = $syncService->dispatchSyncJobs();
            }

            return response()->json([
                'success' => true,
                'message' => 'Backup database selesai!',
                'log' => $result,
                'sync_dispatched' => $syncJobCount,
            ]);
        } catch (Exception $e) {
            Log::error('Backup Manual Gagal: '.$e->getMessage());

            $latestLog = BackupLog::orderBy('id', 'desc')->first();
            $logData = $latestLog ? [
                'id' => $latestLog->id,
                'filename' => $latestLog->filename,
                'type' => $latestLog->type,
                'size' => $latestLog->size,
                'encrypted' => $latestLog->encrypted,
                'drive_uploaded' => $latestLog->drive_uploaded,
                'drive_file_id' => $latestLog->drive_file_id,
                'drive_error' => $latestLog->drive_error,
                'status' => $latestLog->status,
                'duration' => $latestLog->duration,
                'formatted_size' => '-',
                'formatted_date' => $latestLog->created_at->format('d-m-Y H:i:s'),
            ] : null;

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'log' => $logData,
            ], 500);
        }
    }

    /**
     * Download a specific backup file.
     *
     * Keamanan:
     * - basename() mencegah path traversal
     * - Regex pattern memastikan hanya file backup sah yang bisa didownload
     * - Verifikasi ke BackupLog memastikan file terdaftar di sistem
     */
    public function downloadBackup(string $filename): BinaryFileResponse|RedirectResponse
    {
        $safeFilename = basename($filename);

        // Validasi nama file — hanya izinkan pola backup yang dikenal
        // Mencegah download file sembarang di direktori backups
        if (! preg_match('/^backup_[\w\-]+\.(zip|enc)$/', $safeFilename)) {
            abort(403, 'Nama file backup tidak valid.');
        }

        // Verifikasi file ada di database log — bukan file arbitrary
        if (! BackupLog::where('filename', $safeFilename)->exists()) {
            abort(404, 'Backup tidak ditemukan di catatan sistem.');
        }

        $filePath = storage_path('app/backups/'.$safeFilename);

        if (! file_exists($filePath)) {
            return back()->withErrors(['error' => 'Berkas backup tidak ditemukan di penyimpanan.']);
        }

        return response()->download($filePath);
    }

    /**
     * Delete a specific backup file and its log.
     *
     * Keamanan: validasi pattern + cek database sebelum hapus.
     */
    public function deleteBackup(string $filename): JsonResponse
    {
        try {
            $safeFilename = basename($filename);

            // Validasi pola nama file backup yang sah
            if (! preg_match('/^backup_[\w\-]+\.(zip|enc)$/', $safeFilename)) {
                return response()->json(['success' => false, 'message' => 'Nama file backup tidak valid.'], 403);
            }

            // Pastikan ada di database log
            if (! BackupLog::where('filename', $safeFilename)->exists()) {
                return response()->json(['success' => false, 'message' => 'Backup tidak ditemukan di catatan sistem.'], 404);
            }

            $filePath = storage_path('app/backups/'.$safeFilename);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            BackupLog::where('filename', $safeFilename)->delete();

            return response()->json(['success' => true, 'message' => 'Berkas backup berhasil dihapus.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: '.$e->getMessage()], 500);
        }
    }

    /**
     * Verify and test-decrypt an encrypted backup file.
     *
     * Keamanan:
     * - Nama temp file menggunakan random string (bukan time()) — mencegah race condition
     * - File temp menggunakan prefix dot (.) agar tidak bisa didownload via downloadBackup
     * - Block finally memastikan temp file selalu dihapus meski terjadi exception
     */
    public function verifyBackup(Request $request, BackupService $backupService): JsonResponse
    {
        $request->validate([
            'filename' => ['required', 'string'],
            'passphrase' => ['required', 'string'],
        ]);

        $safeFilename = basename($request->input('filename'));

        // Validasi pola nama file backup yang sah
        if (! preg_match('/^backup_[\w\-]+\.(zip|enc)$/', $safeFilename)) {
            return response()->json(['success' => false, 'message' => 'Nama file backup tidak valid.'], 403);
        }

        $filePath = storage_path('app/backups/'.$safeFilename);
        $passphrase = $request->input('passphrase');

        if (! file_exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'Berkas backup tidak ditemukan.'], 404);
        }

        // Gunakan random string (bukan time()) untuk mencegah predictable filename + race condition
        // Prefix dot (.) agar tidak match regex download/delete yang mewajibkan "backup_" prefix
        $tempDecryptedFile = storage_path('app/backups/.tmp_verify_'.Str::random(32).'.zip');

        try {
            $backupService->decryptFile($filePath, $tempDecryptedFile, $passphrase);

            $zip = new \ZipArchive;
            if ($zip->open($tempDecryptedFile) !== true) {
                throw new Exception('Gagal membuka ZIP hasil dekripsi. File mungkin rusak.');
            }

            $filesReport = [];
            $hasDbDump = false;
            $hasStorage = false;

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $filesReport[] = ['name' => $stat['name'], 'size' => $stat['size']];

                if ($stat['name'] === 'database_dump.sql') {
                    $hasDbDump = true;
                }
                if (str_starts_with($stat['name'], 'storage_uploads/')) {
                    $hasStorage = true;
                }
            }

            $zip->close();

            return response()->json([
                'success' => true,
                'message' => 'Validasi sukses! File berhasil didekripsi dan terbukti valid.',
                'report' => [
                    'filename' => $safeFilename,
                    'has_db_dump' => $hasDbDump,
                    'has_storage' => $hasStorage,
                    'total_files' => count($filesReport),
                    'files' => array_slice($filesReport, 0, 10),
                ],
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Dekripsi gagal: '.$e->getMessage()], 400);
        } finally {
            // Selalu hapus file temp — meskipun terjadi exception
            if (file_exists($tempDecryptedFile)) {
                unlink($tempDecryptedFile);
            }
        }
    }

    /**
     * Update storage sync settings.
     */
    public function updateSyncSettings(Request $request): JsonResponse
    {
        $request->validate([
            'sync_enabled' => ['required', 'boolean'],
        ]);

        $config = [
            'enabled' => $request->boolean('sync_enabled'),
        ];

        SecuritySetting::setValue('storage_sync_settings', $config);

        return response()->json([
            'success' => true,
            'message' => 'Konfigurasi sinkronisasi storage berhasil diperbarui!',
        ]);
    }

    /**
     * Trigger storage sync manually from UI — dispatches background jobs.
     */
    public function runStorageSync(StorageSyncService $syncService): JsonResponse
    {
        try {
            $dispatched = $syncService->dispatchSyncJobs();

            // Pre-create storage_sync folder in Drive so user knows where to look
            $driveFolder = null;
            try {
                $driveFolder = $syncService->ensureStorageFolder();
            } catch (Exception $e) {
                Log::warning('StorageSync: Gagal pre-create Drive folder: '.$e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => "{$dispatched} file akan di-sinkronisasi ke Google Drive secara bertahap.",
                'dispatched' => $dispatched,
                'drive_folder' => $driveFolder,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulai sinkronisasi: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get storage sync progress.
     */
    public function getSyncProgress(StorageSyncService $syncService): JsonResponse
    {
        return response()->json($syncService->getSyncProgress());
    }

    /**
     * Clear all file sync logs.
     */
    public function clearSyncLogs(): JsonResponse
    {
        try {
            $deleted = FileSyncLog::count();
            FileSyncLog::truncate();

            Log::info("StorageSync: Semua log sinkronisasi telah dibersihkan ({$deleted} record).");

            return response()->json([
                'success' => true,
                'message' => "{$deleted} log sinkronisasi berhasil dibersihkan.",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan log: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get sync logs with pagination.
     */
    public function getSyncLogs(Request $request, StorageSyncService $syncService): JsonResponse
    {
        $page = max(1, $request->integer('page', 1));
        $perPage = min(50, max(10, $request->integer('per_page', 15)));

        return response()->json(array_merge(
            ['success' => true],
            $syncService->getSyncLogsPaginated($page, $perPage)
        ));
    }

    /**
     * Return current backup progress for UI polling.
     */
    public function getProgress(BackupService $backupService): JsonResponse
    {
        return response()->json($backupService->getProgress());
    }

    /**
     * Return full backup log details via AJAX.
     */
    public function getLogDetails(int $id): JsonResponse
    {
        try {
            $log = BackupLog::find($id);

            if (! $log) {
                return response()->json(['success' => false, 'message' => 'Detail log tidak ditemukan.'], 404);
            }

            $backupSettings = SecuritySetting::getValue('backup_settings', []);
            $scheduleLabel = match ($backupSettings['schedule'] ?? 'daily') {
                'daily' => 'Harian (00:00)',
                'weekly' => 'Mingguan (Minggu 00:00)',
                'monthly' => 'Bulanan (Tgl 1 00:00)',
                'custom' => 'Kustom: '.($backupSettings['cron_expression'] ?? '-'),
                default => '-',
            };

            return response()->json([
                'success' => true,
                'log' => $log,
                'formatted_size' => $log->formatted_size,
                'formatted_date' => $log->created_at?->format('d-m-Y H:i:s') ?? '-',
                'schedule_label' => $scheduleLabel,
                'retention_days' => $backupSettings['retention_days'] ?? 30,
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memuat detail: '.$e->getMessage()], 500);
        }
    }
}
