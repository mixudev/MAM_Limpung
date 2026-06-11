<?php

namespace App\Http\Controllers\Dashboard\Security;

use App\Http\Controllers\Controller;
use App\Mail\BaseMail;
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
     * Show the Security settings page.
     */
    public function index(): View
    {
        $securityCredentials = SecuritySetting::getValue('security_credentials', []);

        // Google Service Account (Workspace)
        $hasGoogleCredentials = ! empty($securityCredentials['google_service_account_json']);
        $maskedGoogleJson = $hasGoogleCredentials ? '[Kredensial Google Service Account Tersimpan Secara Aman]' : '';

        $clientEmail = '-';
        if ($hasGoogleCredentials) {
            try {
                $decryptedJson = Crypt::decryptString($securityCredentials['google_service_account_json']);
                $credObj = json_decode($decryptedJson, true);
                $clientEmail = $credObj['client_email'] ?? '-';
            } catch (Exception) {
                $clientEmail = 'Error membaca email Service Account';
            }
        }

        // Google OAuth2 (personal Drive)
        $hasOAuth2Credentials = false;
        $encryptedOAuth2 = $securityCredentials['google_oauth2_credentials'] ?? '';

        if (! empty($encryptedOAuth2)) {
            try {
                $tokenData = json_decode(Crypt::decryptString($encryptedOAuth2), true);
                $hasOAuth2Credentials = ! empty($tokenData['refresh_token']);
            } catch (Exception) {
                // Silently ignore decryption errors; treat as not configured
            }
        }

        $hasOAuth2EnvCredentials = ! empty(config('services.google_drive_oauth2.client_id'))
            && ! empty(config('services.google_drive_oauth2.client_secret'));

        // SMTP — read from config (driven by .env), never from DB
        $hasSmtpCredentials = BaseMail::isConfigured();
        $smtpConfig = BaseMail::getSmtpConfig();

        return view('dashboard.admin.security.settings', [
            'hasGoogleCredentials' => $hasGoogleCredentials,
            'maskedGoogleJson' => $maskedGoogleJson,
            'clientEmail' => $clientEmail,
            'hasOAuth2Credentials' => $hasOAuth2Credentials,
            'hasOAuth2EnvCredentials' => $hasOAuth2EnvCredentials,
            'hasSmtpCredentials' => $hasSmtpCredentials,
            'smtpConfig' => $smtpConfig,
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
            if (json_decode($newJsonInput, true) === null || json_last_error() !== JSON_ERROR_NONE) {
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
     * Test SMTP Connection — mengirim email uji ke alamat email yang ditentukan.
     *
     * Keamanan: dibatasi hanya super-admin (middleware) dan rate-limited.
     * Email tujuan divalidasi format-nya saja — super-admin dipercaya untuk tes ke email lain.
     */
    public function testSmtpConnection(Request $request, SmtpService $smtpService): JsonResponse
    {
        $request->validate([
            'test_email' => ['required', 'email:rfc,dns'],
        ], [
            'test_email.required' => 'Alamat email tujuan uji coba wajib diisi.',
            'test_email.email' => 'Format alamat email tidak valid.',
        ]);

        $targetEmail = $request->input('test_email');

        try {
            $smtpService->testConnection($targetEmail);

            return response()->json([
                'success' => true,
                'message' => 'Email uji coba berhasil dikirim ke '.$targetEmail.'. Silakan cek inbox.',
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
