<?php

namespace App\Http\Controllers\Dashboard\Backup;

use App\Http\Controllers\Controller;
use App\Models\BackupLog;
use App\Models\SecuritySetting;
use App\Services\BackupService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            'backup_storage' => true,
            'encryption_enabled' => true,
            'google_drive_enabled' => false,
            'google_drive_folder_id' => '',
            'retention_days' => 30,
            'passphrase' => '',
        ];
        $backupSettings = array_merge($defaults, SecuritySetting::getValue('backup_settings', []));

        $hasPassphrase = ! empty($backupSettings['passphrase']);
        $hasGoogleCredentials = ! empty(SecuritySetting::getValue('security_credentials', [])['google_service_account_json'] ?? '');

        $backupHistory = BackupLog::orderBy('created_at', 'desc')->get();
        $storageDirs = $backupService->getStorageDirectories();
        $selectedFolders = $backupSettings['storage_folders'] ?? [];

        return view('dashboard.admin.backup.index', [
            'backupSettings' => $backupSettings,
            'hasPassphrase' => $hasPassphrase,
            'hasGoogleCredentials' => $hasGoogleCredentials,
            'backupHistory' => $backupHistory,
            'storageDirs' => $storageDirs,
            'selectedFolders' => $selectedFolders,
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
            'storage_folders' => ['nullable', 'array'],
            'storage_folders.*' => ['string'],
        ]);

        $currentSettings = SecuritySetting::getValue('backup_settings', []);

        $config = [
            'enabled' => $request->has('enabled'),
            'schedule' => $request->input('schedule'),
            'cron_expression' => $request->input('cron_expression', '0 0 * * *'),
            'backup_db' => $request->has('backup_db'),
            'backup_storage' => $request->has('backup_storage'),
            'storage_folders' => $request->has('backup_storage') ? ($request->input('storage_folders', [])) : [],
            'encryption_enabled' => $request->has('encryption_enabled'),
            'passphrase' => $currentSettings['passphrase'] ?? '',
            'google_drive_enabled' => $request->has('google_drive_enabled'),
            'google_drive_folder_id' => $request->input('google_drive_folder_id'),
            'retention_days' => (int) $request->input('retention_days', 30),
        ];

        if ($config['encryption_enabled'] && empty($config['passphrase'])) {
            return back()->withErrors(['error' => 'Buat kunci enkripsi terlebih dahulu sebelum mengaktifkan enkripsi backup.'])->withInput();
        }

        SecuritySetting::setValue('backup_settings', $config);

        return back()->with('success', 'Konfigurasi backup berhasil diperbarui!');
    }

    /**
     * Generate a new encryption key.
     */
    public function generateKey(Request $request): RedirectResponse
    {
        $request->validate(['confirm_password' => ['required', 'string']]);

        if (! Hash::check($request->input('confirm_password'), $request->user()->password)) {
            return back()->withErrors(['error' => 'Kata sandi konfirmasi salah.']);
        }

        $backupSettings = SecuritySetting::getValue('backup_settings', []);

        try {
            $randomKey = bin2hex(random_bytes(32));
            $backupSettings['passphrase'] = Crypt::encryptString($randomKey);
            SecuritySetting::setValue('backup_settings', $backupSettings);

            return back()->with('success', 'Kunci enkripsi baru berhasil dibuat! Silakan unduh kunci Anda segera.');
        } catch (Exception $e) {
            Log::error('Backup: Gagal men-generate kunci: '.$e->getMessage());

            return back()->withErrors(['error' => 'Gagal membuat kunci enkripsi: '.$e->getMessage()]);
        }
    }

    /**
     * Download encryption key as a text file.
     */
    public function downloadKey(Request $request): StreamedResponse|RedirectResponse
    {
        $request->validate(['confirm_password' => ['required', 'string']]);

        if (! Hash::check($request->input('confirm_password'), $request->user()->password)) {
            return back()->withErrors(['error' => 'Kata sandi konfirmasi salah.']);
        }

        $backupSettings = SecuritySetting::getValue('backup_settings', []);
        $encryptedPass = $backupSettings['passphrase'] ?? '';

        if (empty($encryptedPass)) {
            return back()->withErrors(['error' => 'Kunci enkripsi belum dibuat.']);
        }

        try {
            $decryptedPass = Crypt::decryptString($encryptedPass);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal mendekripsi kunci: '.$e->getMessage()]);
        }

        $filename = 'kunci_enkripsi_backup_'.date('Ymd').'.txt';
        $content = "=== KUNCI ENKRIPSI BACKUP AMAN MAM LIMPUNG ===\n".
                   'Tanggal Dibuat: '.date('Y-m-d H:i:s')."\n".
                   'Kunci Dekripsi (Passphrase): '.$decryptedPass."\n\n".
                   "PENTING: Simpan berkas ini di tempat yang aman.\n".
                   "Cara mendekripsi via CLI:\n".
                   "openssl enc -d -aes-256-cbc -pbkdf2 -iter 10000 -in [nama_file].enc -out [nama_file].zip -pass pass:{$decryptedPass}\n";

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, ['Content-Type' => 'text/plain']);
    }

    /**
     * Run manual backup — returns JSON for AJAX terminal UI.
     */
    public function runBackup(BackupService $backupService): JsonResponse
    {
        try {
            $result = $backupService->runBackup(false);

            return response()->json([
                'success' => true,
                'message' => 'Proses backup selesai dengan sukses!',
                'log' => $result,
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
     */
    public function downloadBackup(string $filename): BinaryFileResponse|RedirectResponse
    {
        $safeFilename = basename($filename);
        $filePath = storage_path('app/backups/'.$safeFilename);

        if (! file_exists($filePath)) {
            return back()->withErrors(['error' => 'Berkas backup tidak ditemukan.']);
        }

        return response()->download($filePath);
    }

    /**
     * Delete a specific backup file and its log.
     */
    public function deleteBackup(string $filename): JsonResponse
    {
        try {
            $safeFilename = basename($filename);
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
     */
    public function verifyBackup(Request $request, BackupService $backupService): JsonResponse
    {
        $request->validate([
            'filename' => ['required', 'string'],
            'passphrase' => ['required', 'string'],
        ]);

        $safeFilename = basename($request->input('filename'));
        $filePath = storage_path('app/backups/'.$safeFilename);
        $passphrase = $request->input('passphrase');

        if (! file_exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'Berkas backup tidak ditemukan.'], 404);
        }

        $tempDecryptedFile = storage_path('app/backups/temp_verify_'.time().'.zip');

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

            if (file_exists($tempDecryptedFile)) {
                unlink($tempDecryptedFile);
            }

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
            if (file_exists($tempDecryptedFile)) {
                unlink($tempDecryptedFile);
            }

            return response()->json(['success' => false, 'message' => 'Dekripsi gagal: '.$e->getMessage()], 400);
        }
    }

    /**
     * Return storage directories as JSON for AJAX scan.
     */
    public function getStorageDirectories(BackupService $backupService): JsonResponse
    {
        try {
            $directories = $backupService->getStorageDirectories();
            $backupSettings = SecuritySetting::getValue('backup_settings', []);

            return response()->json([
                'success' => true,
                'directories' => $directories,
                'selected_folders' => $backupSettings['storage_folders'] ?? [],
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memindai direktori: '.$e->getMessage()], 500);
        }
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

            return response()->json([
                'success' => true,
                'log' => $log,
                'formatted_size' => $log->formatted_size,
                'formatted_date' => $log->created_at->format('d-m-Y H:i:s'),
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memuat detail: '.$e->getMessage()], 500);
        }
    }
}
