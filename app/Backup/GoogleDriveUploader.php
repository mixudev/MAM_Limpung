<?php

namespace App\Backup;

use App\Models\PpdbSetting;
use App\Models\SecuritySetting;
use Exception;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class GoogleDriveUploader
{
    /**
     * Upload a file to Google Drive using the Service Account credentials.
     *
     * @throws Exception
     */
    public function upload(string $filePath, string $filename): string
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

        $metadataOpts = ['name' => $filename];
        if (! empty($folderId)) {
            $metadataOpts['parents'] = [$folderId];
        }

        $content = file_get_contents($filePath);

        if ($content === false) {
            throw new Exception('Gagal membaca berkas backup untuk diunggah ke Google Drive.');
        }

        $file = $driveService->files->create(new DriveFile($metadataOpts), [
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
     * Retrieve and decrypt the Google Service Account credentials array.
     *
     * @return array<string, mixed>|null
     */
    public function getServiceAccountCredentials(): ?array
    {
        $securityCredentials = SecuritySetting::getValue('security_credentials', []);
        $encryptedJson = $securityCredentials['google_service_account_json'] ?? '';

        if (empty($encryptedJson)) {
            // Fallback: check google_sheets config
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
}
