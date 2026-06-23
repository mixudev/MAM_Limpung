<?php

namespace App\Http\Controllers\Dashboard\Security;

use App\Http\Controllers\Controller;
use App\Models\SecuritySetting;
use Exception;
use Google\Client as GoogleClient;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class GoogleDriveOAuthController extends Controller
{
    /**
     * Redirect to Google consent screen using credentials from .env.
     * Client ID & Secret are never stored via the web interface — they live in .env only.
     */
    public function authorize(): RedirectResponse
    {
        $clientId = config('services.google_drive_oauth2.client_id');
        $clientSecret = config('services.google_drive_oauth2.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            return back()->withErrors([
                'google_oauth2' => 'GOOGLE_DRIVE_OAUTH2_CLIENT_ID dan GOOGLE_DRIVE_OAUTH2_CLIENT_SECRET belum diisi di file .env.',
            ]);
        }

        // Store a CSRF state token in session to verify on callback
        $state = bin2hex(random_bytes(16));
        Session::put('google_oauth2_state', $state);

        $authUrl = $this->buildClient($clientId, $clientSecret, $state)->createAuthUrl();

        return redirect($authUrl);
    }

    /**
     * Handle Google OAuth2 callback and persist the refresh token.
     * This route is intentionally outside the auth middleware — Google redirects here
     * without carrying the Laravel session cookie, so we verify identity via state token.
     */
    public function handleCallback(Request $request): RedirectResponse
    {
        $code = $request->query('code');
        $error = $request->query('error');
        $state = $request->query('state');

        if ($error) {
            return redirect()->route('admin.security.index')
                ->withErrors(['google_oauth2' => 'Otorisasi Google dibatalkan: '.$error]);
        }

        if (empty($code)) {
            return redirect()->route('admin.security.index')
                ->withErrors(['google_oauth2' => 'Authorization code tidak diterima dari Google.']);
        }

        // Verify state to prevent CSRF
        $expectedState = Session::get('google_oauth2_state');
        if (empty($state) || $state !== $expectedState) {
            return redirect()->route('admin.security.index')
                ->withErrors(['google_oauth2' => 'State token tidak valid. Silakan coba otorisasi ulang.']);
        }

        Session::forget('google_oauth2_state');

        $clientId = config('services.google_drive_oauth2.client_id');
        $clientSecret = config('services.google_drive_oauth2.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('admin.security.index')
                ->withErrors(['google_oauth2' => 'Kredensial OAuth2 tidak ditemukan di .env. Isi GOOGLE_DRIVE_OAUTH2_CLIENT_ID dan GOOGLE_DRIVE_OAUTH2_CLIENT_SECRET.']);
        }

        try {
            $client = $this->buildClient($clientId, $clientSecret);

            if (app()->environment('local')) {
                $client->setHttpClient(new GuzzleClient(['verify' => false]));
            }

            $token = $client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                throw new Exception('Google error: '.($token['error_description'] ?? $token['error']));
            }

            if (empty($token['refresh_token'])) {
                return redirect()->route('admin.security.index')
                    ->withErrors(['google_oauth2' => 'Google tidak mengembalikan Refresh Token. Pastikan "access_type=offline" dan revoke akses lama di myaccount.google.com/permissions, lalu coba lagi.']);
            }

            // Persist only the token — Client ID & Secret stay in .env
            $securityCredentials = SecuritySetting::getValue('security_credentials', []);
            $securityCredentials['google_oauth2_credentials'] = Crypt::encryptString(json_encode([
                'refresh_token' => $token['refresh_token'],
                'access_token' => $token['access_token'] ?? '',
                'expires_in' => $token['expires_in'] ?? 3600,
                'created' => $token['created'] ?? time(),
            ]));
            SecuritySetting::setValue('security_credentials', $securityCredentials);

            Log::info('Backup: OAuth2 Google Drive berhasil diotorisasi dan refresh token tersimpan.');

            return redirect()->route('admin.security.index')
                ->with('success', 'Google Drive berhasil diotorisasi! Backup ke Google Drive personal kini siap digunakan.');
        } catch (Exception $e) {
            Log::error('Backup OAuth2 Callback Error: '.$e->getMessage());

            return redirect()->route('admin.security.index')
                ->withErrors(['google_oauth2' => 'Gagal menukar kode otorisasi: '.$e->getMessage()]);
        }
    }

    /**
     * Revoke and delete the stored OAuth2 token.
     */
    public function revoke(): RedirectResponse
    {
        try {
            $securityCredentials = SecuritySetting::getValue('security_credentials', []);
            $encryptedJson = $securityCredentials['google_oauth2_credentials'] ?? '';

            if (! empty($encryptedJson)) {
                $tokenData = json_decode(Crypt::decryptString($encryptedJson), true);

                if (! empty($tokenData['access_token'])) {
                    $clientId = config('services.google_drive_oauth2.client_id');
                    $clientSecret = config('services.google_drive_oauth2.client_secret');

                    if ($clientId && $clientSecret) {
                        $client = $this->buildClient($clientId, $clientSecret);

                        if (app()->environment('local')) {
                            $client->setHttpClient(new GuzzleClient(['verify' => false]));
                        }

                        $client->setAccessToken(['access_token' => $tokenData['access_token']]);
                        $client->revokeToken();
                    }
                }
            }

            unset($securityCredentials['google_oauth2_credentials']);
            SecuritySetting::setValue('security_credentials', $securityCredentials);

            return redirect()->route('admin.security.index')
                ->with('success', 'Otorisasi Google Drive berhasil dicabut.');
        } catch (Exception $e) {
            Log::error('Backup OAuth2 Revoke Error: '.$e->getMessage());

            // Tetap hapus token dari DB meskipun revoke ke Google gagal
            // (token mungkin sudah expired atau akun sudah tidak valid)
            try {
                $securityCredentials = SecuritySetting::getValue('security_credentials', []);
                unset($securityCredentials['google_oauth2_credentials']);
                SecuritySetting::setValue('security_credentials', $securityCredentials);
            } catch (Exception) {
                // silent
            }

            return redirect()->route('admin.security.index')
                ->with('success', 'Token lokal berhasil dihapus. Catatan: pencabutan ke server Google gagal ('.$e->getMessage().'), tapi akses telah dicabut dari sisi sistem.');
        }
    }

    /**
     * Build a pre-configured GoogleClient for OAuth2 authorization.
     */
    private function buildClient(string $clientId, string $clientSecret, ?string $state = null): GoogleClient
    {
        $client = new GoogleClient;
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri(route('admin.security.google-drive.callback'));
        $client->addScope('https://www.googleapis.com/auth/drive.file');
        $client->setAccessType('offline');
        $client->setPrompt('consent'); // Force consent to always get a refresh_token

        if ($state !== null) {
            $client->setState($state);
        }

        return $client;
    }
}
