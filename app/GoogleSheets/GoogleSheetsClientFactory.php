<?php

namespace App\GoogleSheets;

use App\Models\SecuritySetting;
use Exception;
use Google\Client as GoogleClient;
use Google\Service\Sheets as GoogleSheets;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Crypt;

class GoogleSheetsClientFactory
{
    /**
     * Build an authenticated Google Sheets API service instance.
     *
     * @param  array<string, mixed>  $config  The google_sheets PpdbSetting config array.
     *
     * @throws Exception
     */
    public function make(array $config): GoogleSheets
    {
        $securityCredentials = SecuritySetting::getValue('security_credentials', []);
        $serviceAccountJson = $securityCredentials['google_service_account_json'] ?? '';

        if (empty($config['spreadsheet_id']) || empty($serviceAccountJson)) {
            throw new Exception('Google Sheets belum dikonfigurasi secara lengkap. Atur Google Service Account di halaman Keamanan.');
        }

        try {
            $decryptedJson = Crypt::decryptString($serviceAccountJson);
            $credentials = json_decode($decryptedJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Format kredensial Service Account JSON tidak valid.');
            }
        } catch (Exception $e) {
            throw new Exception('Gagal membaca kredensial Service Account: '.$e->getMessage());
        }

        $client = new GoogleClient;
        $client->setAuthConfig($credentials);
        $client->addScope(GoogleSheets::SPREADSHEETS);

        if (app()->environment('local')) {
            $client->setHttpClient(new GuzzleClient(['verify' => false]));
        }

        return new GoogleSheets($client);
    }

    /**
     * Decrypt and return the raw credentials array for direct use (e.g. reading client_email).
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function decryptCredentials(): array
    {
        $securityCredentials = SecuritySetting::getValue('security_credentials', []);
        $serviceAccountJson = $securityCredentials['google_service_account_json'] ?? '';

        if (empty($serviceAccountJson)) {
            throw new Exception('Kredensial Google Service Account tidak ditemukan.');
        }

        $decryptedJson = Crypt::decryptString($serviceAccountJson);
        $credentials = json_decode($decryptedJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Format kredensial Service Account JSON tidak valid.');
        }

        return $credentials;
    }
}
