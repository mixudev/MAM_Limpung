<?php

namespace App\Backup;

use App\Models\SecuritySetting;
use Exception;
use Google\Client as GoogleClient;
use Google\Http\MediaFileUpload;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class GoogleDriveUploader
{
    /**
     * Upload a file to Google Drive.
     * Automatically selects OAuth2 (personal Drive) or Service Account (Workspace) based on stored credentials.
     *
     * @throws Exception
     */
    /**
     * Get or create the root folder for backups.
     * Returns google_drive_folder_id if set (shared folder),
     * otherwise creates an app-named folder in My Drive root.
     *
     * @throws Exception
     */
    public function getOrCreateRootFolder(): string
    {
        $backupSettings = SecuritySetting::getValue('backup_settings', []);
        $sharedFolderId = $backupSettings['google_drive_folder_id'] ?? null;

        if (! empty($sharedFolderId)) {
            return $sharedFolderId;
        }

        $appName = trim(config('app.name'));

        return $this->ensureFolderPath(($appName ? $appName.' - ' : '').'Backup');
    }

    public function upload(string $filePath, string $filename, ?string $parentFolderId = null): string
    {
        if (! file_exists($filePath)) {
            throw new Exception("File backup tidak ditemukan di path: {$filePath}");
        }

        $fileSize = filesize($filePath);
        if ($fileSize === false || $fileSize === 0) {
            throw new Exception('File backup kosong atau tidak dapat dibaca ukurannya.');
        }

        $client = $this->buildAuthenticatedClient();

        $driveService = new GoogleDrive($client);

        $folderId = $parentFolderId ?? $this->getOrCreateRootFolder();

        $metadataOpts = ['name' => $filename, 'parents' => [$folderId]];

        // Gunakan resumable chunked upload untuk menangani file besar tanpa OOM
        $client->setDefer(true);
        $file = new DriveFile($metadataOpts);
        $request = $driveService->files->create($file, ['supportsAllDrives' => true]);

        $chunkSize = 1 * 1024 * 1024; // 1MB per chunk
        $media = new MediaFileUpload($client, $request, 'application/octet-stream', null, true, $chunkSize);
        $media->setFileSize($fileSize);

        $uploadResult = null;
        $handle = fopen($filePath, 'rb');

        if ($handle === false) {
            $client->setDefer(false);
            throw new Exception('Tidak dapat membuka file untuk dibaca: '.$filePath);
        }

        while ($uploadResult === null && ! feof($handle)) {
            $chunk = fread($handle, $chunkSize);
            $uploadResult = $media->nextChunk($chunk);
            $progress = $media->getProgress();
            if ($progress > 0) {
                $pct = round(($progress / $fileSize) * 100, 1);
                Log::info("Backup: Upload Google Drive — {$pct}% ({$progress}/{$fileSize} bytes)");
            }
        }

        fclose($handle);
        $client->setDefer(false);

        if (empty($uploadResult->id)) {
            throw new Exception('Gagal mengunggah berkas ke Google Drive (tidak ada ID berkas dikembalikan).');
        }

        Log::info("Backup: File {$filename} ({$fileSize} bytes) berhasil diunggah ke Google Drive. ID: {$uploadResult->id}");

        return $uploadResult->id;
    }

    /**
     * Ensure a folder path exists in Google Drive, creating folders recursively as needed.
     * Returns the ID of the deepest folder.
     *
     * @throws Exception
     */
    public function ensureFolderPath(string $folderPath, ?string $rootFolderId = null): string
    {
        $parts = array_filter(explode('/', $folderPath));
        if (empty($parts)) {
            return $rootFolderId ?? 'root';
        }

        $client = $this->buildAuthenticatedClient();
        $driveService = new GoogleDrive($client);
        $parentId = $rootFolderId ?? 'root';

        foreach ($parts as $part) {
            $parentId = $this->findOrCreateFolder($driveService, $part, $parentId);
        }

        return $parentId;
    }

    /**
     * Find a folder by name under a parent, or create it if it doesn't exist.
     *
     * @throws Exception
     */
    private function findOrCreateFolder(GoogleDrive $driveService, string $name, string $parentId): string
    {
        $query = "mimeType='application/vnd.google-apps.folder' and name='".str_replace("'", "\\'", $name)."' and '{$parentId}' in parents and trashed=false";
        $results = $driveService->files->listFiles([
            'q' => $query,
            'fields' => 'files(id)',
            'pageSize' => 1,
            'supportsAllDrives' => true,
            'includeItemsFromAllDrives' => true,
        ]);

        $files = $results->getFiles();
        if (! empty($files)) {
            return $files[0]->getId();
        }

        $folder = new DriveFile([
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentId],
        ]);

        $created = $driveService->files->create($folder, ['fields' => 'id', 'supportsAllDrives' => true]);

        return $created->getId();
    }

    /**
     * Build an authenticated GoogleClient using OAuth2 (personal Drive) if available,
     * falling back to Service Account (Google Workspace) credentials.
     *
     * @throws Exception
     */
    public function buildAuthenticatedClient(): GoogleClient
    {
        $client = new GoogleClient;
        $client->addScope(GoogleDrive::DRIVE_FILE);

        // -----------------------------------------------------------------------
        //  SSL CA bundle — di Windows, PHP sering tidak punya CA bundle bawaan.
        //  Gunakan cacert.pem yang disimpan di storage/app jika ada,
        //  lalu fallback ke curl.cainfo dari php.ini.
        //  Di environment local (development), SSL verify dinonaktifkan sebagai
        //  last resort agar tidak blocking development. Di production, harus verify=true.
        // -----------------------------------------------------------------------
        $cacertPath = storage_path('app/cacert.pem');

        if (file_exists($cacertPath)) {
            $client->setHttpClient(new GuzzleClient(['verify' => $cacertPath]));
        } elseif (! empty(ini_get('curl.cainfo')) && file_exists(ini_get('curl.cainfo'))) {
            $client->setHttpClient(new GuzzleClient(['verify' => ini_get('curl.cainfo')]));
        } elseif (app()->environment('local')) {
            // Di Windows local dev, CA bundle sering tidak tersedia — nonaktifkan SSL verify
            // JANGAN gunakan ini di production
            $client->setHttpClient(new GuzzleClient(['verify' => false]));
        }

        // Try OAuth2 first (works with personal Gmail / Google Drive)
        $oauth2Credentials = $this->getOAuth2Credentials();
        if ($oauth2Credentials) {
            $client->setClientId($oauth2Credentials['client_id']);
            $client->setClientSecret($oauth2Credentials['client_secret']);
            $client->setAccessType('offline');

            // Set access token from stored refresh token
            $accessToken = [
                'access_token' => $oauth2Credentials['access_token'] ?? '',
                'refresh_token' => $oauth2Credentials['refresh_token'],
                'expires_in' => $oauth2Credentials['expires_in'] ?? 0,
                'created' => $oauth2Credentials['created'] ?? 0,
            ];

            $client->setAccessToken($accessToken);

            // Refresh the token if expired
            if ($client->isAccessTokenExpired()) {
                if (empty($oauth2Credentials['refresh_token'])) {
                    throw new Exception('OAuth2 Refresh Token kosong. Silakan otorisasi ulang Google Drive di halaman Keamanan.');
                }

                $newToken = $client->fetchAccessTokenWithRefreshToken($oauth2Credentials['refresh_token']);

                if (isset($newToken['error'])) {
                    throw new Exception('Gagal memperbarui OAuth2 token: '.($newToken['error_description'] ?? $newToken['error']));
                }

                // Persist updated token (includes new access_token + potentially new refresh_token)
                $this->persistUpdatedOAuth2Token($newToken, $oauth2Credentials);
            }

            Log::info('Backup: Menggunakan OAuth2 untuk Google Drive (personal account).');

            return $client;
        }

        // Fallback: Service Account (Google Workspace)
        $serviceAccountCredentials = $this->getServiceAccountCredentials();
        if ($serviceAccountCredentials) {
            $client->setAuthConfig($serviceAccountCredentials);
            $client->addScope(GoogleDrive::DRIVE);

            Log::info('Backup: Menggunakan Service Account untuk Google Drive (Workspace).');

            return $client;
        }

        throw new Exception('Tidak ada kredensial Google Drive yang ditemukan. Silakan konfigurasikan OAuth2 atau Service Account di halaman Keamanan.');
    }

    /**
     * Retrieve OAuth2 credentials: Client ID & Secret from .env, token from DB.
     *
     * @return array<string, mixed>|null
     */
    public function getOAuth2Credentials(): ?array
    {
        $clientId = config('services.google_drive_oauth2.client_id');
        $clientSecret = config('services.google_drive_oauth2.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            return null;
        }

        $securityCredentials = SecuritySetting::getValue('security_credentials', []);
        $encryptedJson = $securityCredentials['google_oauth2_credentials'] ?? '';

        if (empty($encryptedJson)) {
            return null;
        }

        try {
            $tokenData = json_decode(Crypt::decryptString($encryptedJson), true);

            if (empty($tokenData['refresh_token'])) {
                return null;
            }

            return array_merge($tokenData, [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ]);
        } catch (Exception $e) {
            Log::error('Backup: Gagal mendekripsi token OAuth2 Google: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Persist the refreshed access token back to SecuritySetting (token only, not credentials).
     *
     * @param  array<string, mixed>  $newToken
     * @param  array<string, mixed>  $existingCredentials
     */
    private function persistUpdatedOAuth2Token(array $newToken, array $existingCredentials): void
    {
        try {
            $merged = [
                'access_token' => $newToken['access_token'] ?? $existingCredentials['access_token'] ?? '',
                'refresh_token' => $existingCredentials['refresh_token'],
                'expires_in' => $newToken['expires_in'] ?? $existingCredentials['expires_in'] ?? 3600,
                'created' => $newToken['created'] ?? time(),
            ];

            // Preserve new refresh_token if Google rotated it
            if (! empty($newToken['refresh_token'])) {
                $merged['refresh_token'] = $newToken['refresh_token'];
            }

            $securityCredentials = SecuritySetting::getValue('security_credentials', []);
            $securityCredentials['google_oauth2_credentials'] = Crypt::encryptString(json_encode($merged));
            SecuritySetting::setValue('security_credentials', $securityCredentials);
        } catch (Exception $e) {
            Log::warning('Backup: Gagal menyimpan token OAuth2 yang diperbarui: '.$e->getMessage());
        }
    }

    /**
     * Retrieve and decrypt the Google Service Account credentials array (for Workspace accounts).
     *
     * @return array<string, mixed>|null
     */
    public function getServiceAccountCredentials(): ?array
    {
        $securityCredentials = SecuritySetting::getValue('security_credentials', []);
        $encryptedJson = $securityCredentials['google_service_account_json'] ?? '';

        if (empty($encryptedJson)) {
            return null;
        }

        try {
            $decryptedJson = Crypt::decryptString($encryptedJson);

            return json_decode($decryptedJson, true);
        } catch (Exception $e) {
            Log::error('Backup: Gagal mendekripsi kredensial Google Service Account: '.$e->getMessage());

            return null;
        }
    }
}
