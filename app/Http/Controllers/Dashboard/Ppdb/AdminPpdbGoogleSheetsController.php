<?php

namespace App\Http\Controllers\Dashboard\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PpdbSetting;
use App\Services\GoogleSheetsService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class AdminPpdbGoogleSheetsController extends Controller
{
    /**
     * Show Google Sheets settings page.
     */
    public function edit(): View
    {
        $defaults = [
            'spreadsheet_id' => '',
            'is_enabled' => false,
            'service_account_json' => '',
            'split_by_status' => false,
            'header_style' => 'purple',
            'sync_fields' => [
                'no_registrasi',
                'nama_lengkap',
                'nisn',
                'jenis_kelamin',
                'sekolah_asal',
                'no_hp',
                'email',
                'status',
                'tanggal_daftar',
                'custom_fields',
            ],
            'active_sheets' => ['semua', 'diterima', 'pending', 'ditolak', 'ringkasan'],
            'sheet_names' => [
                'semua' => 'Semua Pendaftar',
                'diterima' => 'Siswa Diterima',
                'pending' => 'Dalam Proses',
                'ditolak' => 'Siswa Ditolak',
                'ringkasan' => 'Ringkasan Data',
            ],
        ];

        // Retrieve raw config
        $saved = PpdbSetting::getValue('google_sheets', []);

        // Deep merge names if saved has sheet_names
        if (isset($saved['sheet_names'])) {
            $saved['sheet_names'] = array_merge($defaults['sheet_names'], $saved['sheet_names']);
        }

        $settings = array_merge($defaults, $saved);

        // Mask the service account credentials if it exists to display securely
        $hasCredentials = ! empty($settings['service_account_json']);
        $maskedJson = $hasCredentials
            ? '[Kredensial Service Account Tersimpan Secara Aman]'
            : '';

        // Retrieve saved client email dynamically if credentials exist
        $clientEmail = '-';
        if ($hasCredentials) {
            try {
                $decryptedJson = Crypt::decryptString($settings['service_account_json']);
                $credentials = json_decode($decryptedJson, true);
                $clientEmail = $credentials['client_email'] ?? '-';
            } catch (Exception $e) {
                $clientEmail = 'Eror membaca email Service Account';
            }
        }

        return view('dashboard.admin.ppdb.google_sheets', [
            'settings' => $settings,
            'hasCredentials' => $hasCredentials,
            'maskedJson' => $maskedJson,
            'clientEmail' => $clientEmail,
        ]);
    }

    /**
     * Update Google Sheets settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'spreadsheet_id' => ['required', 'string'],
            'service_account_json' => ['nullable', 'string'],
            'header_style' => ['required', 'string', 'in:plain,purple,emerald,dark'],
            'sync_fields' => ['required', 'array', 'min:1'],
            'active_sheets' => ['nullable', 'array'],
            'sheet_names' => ['nullable', 'array'],
        ], [
            'spreadsheet_id.required' => 'Spreadsheet ID wajib diisi.',
            'sync_fields.required' => 'Pilih minimal satu kolom data untuk disinkronkan.',
        ]);

        $currentSettings = PpdbSetting::getValue('google_sheets', []);
        $newJsonInput = $request->input('service_account_json');

        $encryptedJson = $currentSettings['service_account_json'] ?? '';

        if (! empty($newJsonInput) && strpos($newJsonInput, 'Kredensial Service Account') === false) {
            // Validate JSON format
            $decoded = json_decode($newJsonInput, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['service_account_json' => 'Format kredensial Service Account JSON tidak valid atau rusak.'])->withInput();
            }

            // Encrypt and store the JSON securely
            try {
                $encryptedJson = Crypt::encryptString($newJsonInput);
            } catch (Exception $e) {
                return back()->withErrors(['service_account_json' => 'Gagal mengamankan kredensial JSON.'])->withInput();
            }
        }

        $config = [
            'spreadsheet_id' => $request->input('spreadsheet_id'),
            'is_enabled' => $request->has('is_enabled'),
            'service_account_json' => $encryptedJson,
            'split_by_status' => $request->has('split_by_status'),
            'header_style' => $request->input('header_style', 'purple'),
            'sync_fields' => $request->input('sync_fields', []),
            'active_sheets' => $request->input('active_sheets', ['semua', 'diterima', 'pending', 'ditolak', 'ringkasan']),
            'sheet_names' => $request->input('sheet_names', []),
            'last_connection_checked_at' => null, // Invalidate connection cache on update
            'last_connection_status' => null,
            'last_connection_error' => null,
        ];

        PpdbSetting::setValue('google_sheets', $config);

        return redirect()->route('admin.ppdb.google-sheets.edit')
            ->with('success', 'Pengaturan Google Sheets berhasil disimpan dengan sangat aman!');
    }

    /**
     * Test connection to Google Sheets dynamically via AJAX.
     */
    public function testConnection(Request $request, GoogleSheetsService $sheetsService): JsonResponse
    {
        $settings = PpdbSetting::getValue('google_sheets', []);

        $force = $request->input('force', false);
        $lastCheck = $settings['last_connection_checked_at'] ?? null;

        // Cache connection status for 24 hours (86400 seconds)
        if (! $force && $lastCheck && strtotime($lastCheck) > (time() - 86400)) {
            $isSuccess = ($settings['last_connection_status'] ?? '') === 'connected';
            $errorMessage = $settings['last_connection_error'] ?? null;

            $message = $isSuccess
                ? 'Terhubung (Cached)'
                : ($errorMessage ?? 'Koneksi Terputus (Cached)');

            return response()->json([
                'success' => $isSuccess,
                'message' => $message,
                'client_email' => $settings['last_client_email'] ?? '-',
                'cached' => true,
            ]);
        }

        $status = $sheetsService->testConnection();

        // Save the result to settings cache
        $settings['last_connection_status'] = $status['success'] ? 'connected' : 'failed';
        $settings['last_connection_error'] = $status['success'] ? null : $status['message'];
        $settings['last_connection_checked_at'] = date('Y-m-d H:i:s');
        $settings['last_client_email'] = $status['client_email'] ?? '-';

        PpdbSetting::setValue('google_sheets', $settings);

        return response()->json(array_merge($status, ['cached' => false]));
    }

    /**
     * Sync all existing candidates dynamically via AJAX.
     */
    public function syncNow(GoogleSheetsService $sheetsService): JsonResponse
    {
        $status = $sheetsService->syncAll();

        return response()->json($status);
    }
}
