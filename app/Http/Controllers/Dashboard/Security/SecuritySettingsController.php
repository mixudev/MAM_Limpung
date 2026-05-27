<?php

namespace App\Http\Controllers\Dashboard\Security;

use App\Http\Controllers\Controller;
use App\Models\SecuritySetting;
use App\Services\SmtpService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class SecuritySettingsController extends Controller
{
    /**
     * Show the Security settings page (Credentials only).
     */
    public function index(): View
    {
        // Google Credentials
        $credentials = array_merge(
            ['google_service_account_json' => ''],
            SecuritySetting::getValue('security_credentials', [])
        );

        $hasGoogleCredentials = ! empty($credentials['google_service_account_json']);
        $maskedGoogleJson = $hasGoogleCredentials ? '[Kredensial Google Service Account Tersimpan Secara Aman]' : '';

        $clientEmail = '-';
        if ($hasGoogleCredentials) {
            try {
                $decryptedJson = Crypt::decryptString($credentials['google_service_account_json']);
                $credObj = json_decode($decryptedJson, true);
                $clientEmail = $credObj['client_email'] ?? '-';
            } catch (Exception) {
                $clientEmail = 'Error membaca email Service Account';
            }
        }

        // SMTP Credentials
        $smtpDefaults = [
            'host' => '',
            'port' => 587,
            'username' => '',
            'password_encrypted' => '',
            'encryption' => 'tls',
            'from_address' => '',
            'from_name' => config('app.name', 'MAM Limpung'),
        ];
        $smtpCredentials = array_merge($smtpDefaults, SecuritySetting::getValue('smtp_credentials', []));
        $hasSmtpCredentials = ! empty($smtpCredentials['host']) && ! empty($smtpCredentials['username']);

        return view('dashboard.admin.security.settings', [
            'hasGoogleCredentials' => $hasGoogleCredentials,
            'maskedGoogleJson' => $maskedGoogleJson,
            'clientEmail' => $clientEmail,
            'smtpCredentials' => $smtpCredentials,
            'hasSmtpCredentials' => $hasSmtpCredentials,
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

        if (! empty($newJsonInput) && ! str_contains($newJsonInput, 'Kredensial Google Service Account')) {
            $decoded = json_decode($newJsonInput, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['google_service_account_json' => 'Format JSON tidak valid.'])->withInput();
            }

            try {
                $encryptedJson = Crypt::encryptString($newJsonInput);
            } catch (Exception $e) {
                return back()->withErrors(['google_service_account_json' => 'Gagal mengamankan kredensial JSON.'])->withInput();
            }
        } elseif (empty($newJsonInput)) {
            $encryptedJson = '';
        }

        SecuritySetting::setValue('security_credentials', [
            'google_service_account_json' => $encryptedJson,
        ]);

        return back()->with('success', 'Kredensial Google Service Account berhasil disimpan secara aman!');
    }

    /**
     * Update SMTP Credentials.
     */
    public function updateSmtpCredentials(Request $request): RedirectResponse
    {
        $request->validate([
            'host' => ['required', 'string', 'max:255'],
            'port' => ['required', 'integer', 'min:1', 'max:65535'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string'],
            'encryption' => ['required', 'string', 'in:tls,ssl,none'],
            'from_address' => ['required', 'email', 'max:255'],
            'from_name' => ['required', 'string', 'max:255'],
        ]);

        $currentSmtp = SecuritySetting::getValue('smtp_credentials', []);

        // Only update password if a new one is provided
        $newPassword = $request->input('password');
        $encryptedPassword = $currentSmtp['password_encrypted'] ?? '';

        if (! empty($newPassword) && $newPassword !== '••••••••') {
            try {
                $encryptedPassword = Crypt::encryptString($newPassword);
            } catch (Exception $e) {
                return back()->withErrors(['password' => 'Gagal mengamankan password SMTP.'])->withInput();
            }
        }

        SecuritySetting::setValue('smtp_credentials', [
            'host' => $request->input('host'),
            'port' => (int) $request->input('port'),
            'username' => $request->input('username'),
            'password_encrypted' => $encryptedPassword,
            'encryption' => $request->input('encryption'),
            'from_address' => $request->input('from_address'),
            'from_name' => $request->input('from_name'),
        ]);

        return back()->with('success', 'Konfigurasi SMTP berhasil disimpan!');
    }

    /**
     * Test SMTP Connection — sends a test email to the logged-in admin.
     */
    public function testSmtpConnection(Request $request, SmtpService $smtpService): JsonResponse
    {
        $request->validate([
            'test_email' => ['required', 'email'],
        ]);

        try {
            $smtpService->testConnection($request->input('test_email'));

            return response()->json([
                'success' => true,
                'message' => 'Email uji coba berhasil dikirim ke '.$request->input('test_email').'. Silakan cek inbox Anda.',
            ]);
        } catch (Exception $e) {
            Log::error('SMTP Test Failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Uji koneksi gagal: '.$e->getMessage(),
            ], 422);
        }
    }
}
