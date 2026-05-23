<?php

namespace App\Http\Controllers\Dashboard\Security;

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

class SecuritySettingsController extends Controller
{
    /**
     * Show the Security settings page.
     */
    public function index(BackupService $backupService): View
    {
        $defaultsCredentials = [
            'google_service_account_json' => '',
        ];
        $credentials = array_merge($defaultsCredentials, SecuritySetting::getValue('security_credentials', []));

        $hasGoogleCredentials = ! empty($credentials['google_service_account_json']);
        $maskedGoogleJson = $hasGoogleCredentials ? '[Kredensial Google Service Account Tersimpan Secara Aman]' : '';

        // Get Service Account email if it exists
        $clientEmail = '-';
        if ($hasGoogleCredentials) {
            try {
                $decryptedJson = Crypt::decryptString($credentials['google_service_account_json']);
                $credObj = json_decode($decryptedJson, true);
                $clientEmail = $credObj['client_email'] ?? '-';
            } catch (Exception $e) {
                $clientEmail = 'Eror membaca email Service Account';
            }
        }

        $defaultsBackup = [
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
        $backupSettings = array_merge($defaultsBackup, SecuritySetting::getValue('backup_settings', []));

        $hasPassphrase = ! empty($backupSettings['passphrase']);

        $backupHistory = BackupLog::orderBy('created_at', 'desc')->get();

        $storageDirs = $backupService->getStorageDirectories();
        $selectedFolders = $backupSettings['storage_folders'] ?? [];

        return view('dashboard.admin.security.settings', [
            'hasGoogleCredentials' => $hasGoogleCredentials,
            'maskedGoogleJson' => $maskedGoogleJson,
            'clientEmail' => $clientEmail,
            'backupSettings' => $backupSettings,
            'hasPassphrase' => $hasPassphrase,
            'backupHistory' => $backupHistory,
            'storageDirs' => $storageDirs,
            'selectedFolders' => $selectedFolders,
        ]);
    }

    /**
     * Update Google Service Account Credentials.
     */
    public function updateCredentials(Request $request): RedirectResponse
    {
        $request->validate([
            'google_service_account_json' => ['nullable', 'string'],
        ]);

        $newJsonInput = $request->input('google_service_account_json');
        $currentCredentials = SecuritySetting::getValue('security_credentials', []);
        $encryptedJson = $currentCredentials['google_service_account_json'] ?? '';

        if (! empty($newJsonInput) && strpos($newJsonInput, 'Kredensial Google Service Account') === false) {
            // Validate JSON format
            $decoded = json_decode($newJsonInput, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['google_service_account_json' => 'Format kredensial JSON tidak valid atau rusak.'])->withInput();
            }

            // Encrypt and store the JSON securely
            try {
                $encryptedJson = Crypt::encryptString($newJsonInput);
            } catch (Exception $e) {
                return back()->withErrors(['google_service_account_json' => 'Gagal mengamankan kredensial JSON.'])->withInput();
            }
        } elseif (empty($newJsonInput)) {
            $encryptedJson = ''; // Clear it if empty
        }

        SecuritySetting::setValue('security_credentials', [
            'google_service_account_json' => $encryptedJson,
        ]);

        return back()->with('success', 'Kredensial Google Service Account berhasil disimpan secara aman terpusat!');
    }

    /**
     * Update Backup Settings.
     */
    public function updateBackupSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'schedule' => ['required', 'string', 'in:daily,weekly,monthly,custom'],
            'cron_expression' => ['nullable', 'string'],
            'retention_days' => ['required', 'integer', 'min:1', 'max:365'],
            'google_drive_folder_id' => ['nullable', 'string'],
            'storage_folders' => ['nullable', 'array'],
            'storage_folders.*' => ['string'],
        ], [
            'retention_days.required' => 'Hari retensi wajib diisi.',
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
            return back()->withErrors(['error' => 'Anda harus membuat/men-generate kunci enkripsi terlebih dahulu sebelum mengaktifkan enkripsi backup.'])->withInput();
        }

        SecuritySetting::setValue('backup_settings', $config);

        return back()->with('success', 'Pengaturan Backup otomatis berhasil diperbarui!');
    }

    /**
     * Generate or rotate the encryption key securely.
     */
    public function generateKey(Request $request): RedirectResponse
    {
        $request->validate([
            'confirm_password' => ['required', 'string'],
        ]);

        if (! Hash::check($request->input('confirm_password'), $request->user()->password)) {
            return back()->withErrors(['error' => 'Gagal merotasi kunci: Kata sandi konfirmasi salah.']);
        }

        $backupSettings = SecuritySetting::getValue('backup_settings', []);

        try {
            // Generate a secure 64-character hex key (32 bytes)
            $randomKey = bin2hex(random_bytes(32));
            $backupSettings['passphrase'] = Crypt::encryptString($randomKey);

            SecuritySetting::setValue('backup_settings', $backupSettings);

            return back()->with('success', 'Kunci enkripsi baru berhasil dibuat secara otomatis! Silakan unduh kunci Anda segera.');
        } catch (Exception $e) {
            Log::error('Gagal men-generate kunci backup: '.$e->getMessage());

            return back()->withErrors(['error' => 'Gagal membuat kunci enkripsi baru: '.$e->getMessage()]);
        }
    }

    /**
     * Download encryption key (passphrase) as a text file securely.
     */
    public function downloadKey(Request $request): StreamedResponse|RedirectResponse
    {
        $request->validate([
            'confirm_password' => ['required', 'string'],
        ]);

        if (! Hash::check($request->input('confirm_password'), $request->user()->password)) {
            return back()->withErrors(['error' => 'Gagal mengunduh kunci: Kata sandi konfirmasi salah.']);
        }

        $backupSettings = SecuritySetting::getValue('backup_settings', []);
        $encryptedPass = $backupSettings['passphrase'] ?? '';

        if (empty($encryptedPass)) {
            return back()->withErrors(['error' => 'Kunci enkripsi belum dibuat.']);
        }

        try {
            $decryptedPass = Crypt::decryptString($encryptedPass);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal mendekripsi kunci enkripsi: '.$e->getMessage()]);
        }

        $filename = 'kunci_enkripsi_backup_'.date('Ymd').'.txt';
        $content = "=== KUNCI ENKRIPSI BACKUP AMAN MAM LIMPUNG ===\n".
                   'Tanggal Dibuat: '.date('Y-m-d H:i:s')."\n".
                   'Kunci Dekripsi (Passphrase): '.$decryptedPass."\n\n".
                   "PENTING: Simpan berkas ini di tempat yang aman. Kunci ini digunakan untuk mendekripsi file cadangan (.enc) jika Anda perlu memulihkan data.\n".
                   "Cara mendekripsi via CLI:\n".
                   "openssl enc -d -aes-256-cbc -pbkdf2 -iter 10000 -in [nama_file].enc -out [nama_file].zip -pass pass:{$decryptedPass}\n";

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

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
            Log::error('Dashboard Security Backup Manual Gagal: '.$e->getMessage());

            $latestFailedLog = BackupLog::orderBy('id', 'desc')->first();
            $logData = null;
            if ($latestFailedLog) {
                $logData = [
                    'id' => $latestFailedLog->id,
                    'filename' => $latestFailedLog->filename,
                    'type' => $latestFailedLog->type,
                    'size' => $latestFailedLog->size,
                    'encrypted' => $latestFailedLog->encrypted,
                    'drive_uploaded' => $latestFailedLog->drive_uploaded,
                    'drive_file_id' => $latestFailedLog->drive_file_id,
                    'drive_error' => $latestFailedLog->drive_error,
                    'status' => $latestFailedLog->status,
                    'duration' => $latestFailedLog->duration,
                    'formatted_size' => '-',
                    'formatted_date' => $latestFailedLog->created_at->format('d-m-Y H:i:s'),
                ];
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'log' => $logData,
            ], 500);
        }
    }

    /**
     * Download specific backup file securely with directory traversal check.
     */
    public function downloadBackup(string $filename): BinaryFileResponse|RedirectResponse
    {
        $safeFilename = basename($filename);
        $filePath = storage_path('app/backups/'.$safeFilename);

        if (! file_exists($filePath)) {
            return back()->withErrors(['error' => 'Berkas backup tidak ditemukan di penyimpanan server.']);
        }

        return response()->download($filePath);
    }

    /**
     * Deletespecific backup file securely.
     */
    public function deleteBackup(string $filename): JsonResponse
    {
        try {
            $safeFilename = basename($filename);
            $filePath = storage_path('app/backups/'.$safeFilename);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Sync database logs
            BackupLog::where('filename', $safeFilename)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berkas backup berhasil dihapus permanen.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus berkas: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify and test-decrypt an encrypted backup file from the local storage.
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
            return response()->json([
                'success' => false,
                'message' => 'Berkas backup tidak ditemukan di server.',
            ], 404);
        }

        $tempDecryptedFile = storage_path('app/backups/temp_verify_'.time().'.zip');

        try {
            // Run decryption
            $backupService->decryptFile($filePath, $tempDecryptedFile, $passphrase);

            // Open ZIP to check contents and build report
            $zip = new \ZipArchive;
            if ($zip->open($tempDecryptedFile) !== true) {
                throw new Exception('Gagal membuka berkas ZIP hasil dekripsi. File kemungkinan rusak.');
            }

            $filesReport = [];
            $hasDbDump = false;
            $hasStorage = false;

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $filesReport[] = [
                    'name' => $stat['name'],
                    'size' => $stat['size'],
                ];
                if ($stat['name'] === 'database_dump.sql') {
                    $hasDbDump = true;
                }
                if (strpos($stat['name'], 'storage_uploads/') === 0) {
                    $hasStorage = true;
                }
            }

            $zip->close();

            // Clean temp decrypted file
            if (file_exists($tempDecryptedFile)) {
                unlink($tempDecryptedFile);
            }

            return response()->json([
                'success' => true,
                'message' => 'Validasi Sukses! File berhasil didekripsi dengan sempurna dan sandi terbukti 100% valid.',
                'report' => [
                    'filename' => $safeFilename,
                    'has_db_dump' => $hasDbDump,
                    'has_storage' => $hasStorage,
                    'total_files' => count($filesReport),
                    'files' => array_slice($filesReport, 0, 10), // Limit to top 10 files
                ],
            ]);

        } catch (Exception $e) {
            if (file_exists($tempDecryptedFile)) {
                unlink($tempDecryptedFile);
            }

            return response()->json([
                'success' => false,
                'message' => 'Dekripsi gagal: '.$e->getMessage(),
            ], 400);
        }
    }

    /**
     * Scan storage directories and return JSON.
     */
    public function getStorageDirectories(BackupService $backupService): JsonResponse
    {
        try {
            $directories = $backupService->getStorageDirectories();
            $backupSettings = SecuritySetting::getValue('backup_settings', []);
            $selectedFolders = $backupSettings['storage_folders'] ?? [];

            return response()->json([
                'success' => true,
                'directories' => $directories,
                'selected_folders' => $selectedFolders,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memindai direktori: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get backup log details via AJAX.
     */
    public function getBackupLogDetails(int $id): JsonResponse
    {
        try {
            $log = BackupLog::find($id);
            if (! $log) {
                return response()->json([
                    'success' => false,
                    'message' => 'Detail log backup tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'log' => $log,
                'formatted_size' => $log->formatted_size,
                'formatted_date' => $log->created_at->format('d-m-Y H:i:s'),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail log: '.$e->getMessage(),
            ], 500);
        }
    }
}
