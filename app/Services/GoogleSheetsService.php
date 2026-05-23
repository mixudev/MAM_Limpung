<?php

namespace App\Services;

use App\GoogleSheets\GoogleSheetsClientFactory;
use App\GoogleSheets\SheetFormatter;
use App\GoogleSheets\SheetRowBuilder;
use App\GoogleSheets\SheetSyncManager;
use App\Models\PpdbSetting;
use App\Models\PpdbSiswa;
use App\Models\SecuritySetting;
use Exception;
use Google\Service\Sheets;
use Google\Service\Sheets\ClearValuesRequest;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    public function __construct(
        protected GoogleSheetsClientFactory $clientFactory,
        protected SheetRowBuilder $rowBuilder,
        protected SheetFormatter $formatter,
        protected SheetSyncManager $syncManager,
    ) {}

    /**
     * Test connection to the Google Spreadsheet and Sheet.
     *
     * @return array<string, mixed>
     */
    public function testConnection(): array
    {
        $config = PpdbSetting::getValue('google_sheets', []);
        $securityCredentials = SecuritySetting::getValue('security_credentials', []);
        $serviceAccountJson = $securityCredentials['google_service_account_json'] ?? '';

        if (empty($config) || empty($config['spreadsheet_id']) || empty($serviceAccountJson)) {
            return [
                'success' => false,
                'message' => 'Konfigurasi Google Sheets belum lengkap. Spreadsheet ID di halaman PPDB atau Kredensial JSON di halaman Keamanan belum diisi.',
            ];
        }

        try {
            $service = $this->clientFactory->make($config);
            $spreadsheetId = $config['spreadsheet_id'];

            // Ensure all active sheets exist (creating them dynamically if missing)
            $createdSheets = $this->ensureSheetsExist($service, $spreadsheetId, $config);

            $credentials = $this->clientFactory->decryptCredentials();
            $clientEmail = $credentials['client_email'] ?? 'unknown';

            $message = 'Koneksi berhasil! Sistem berhasil terhubung ke Google Sheets API.';
            if (! empty($createdSheets)) {
                $message .= ' Secara otomatis membuat tab baru yang dibutuhkan: '.implode(', ', $createdSheets).'.';
            }

            return [
                'success' => true,
                'message' => $message,
                'client_email' => $clientEmail,
            ];
        } catch (Exception $e) {
            Log::error('Google Sheets Connection Test Failed: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Koneksi gagal: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Append a single candidate's data to Google Sheets.
     */
    public function appendStudent(PpdbSiswa $siswa): bool
    {
        $config = PpdbSetting::getValue('google_sheets', []);

        if (empty($config) || empty($config['is_enabled']) || ! $config['is_enabled']) {
            return true;
        }

        $activeSheets = $config['active_sheets'] ?? ['semua', 'diterima', 'pending', 'ditolak', 'ringkasan'];

        // If split_by_status is enabled or a summary sheet is active, run a full sync to keep all tabs accurate.
        if (! empty($config['split_by_status']) || in_array('ringkasan', $activeSheets)) {
            $result = $this->syncAll();

            return $result['success'];
        }

        try {
            $service = $this->clientFactory->make($config);
            $spreadsheetId = $config['spreadsheet_id'];
            $sheetNames = $config['sheet_names'] ?? ['semua' => 'Semua Pendaftar'];
            $sheetName = $sheetNames['semua'] ?? 'Semua Pendaftar';

            $this->ensureSheetsExist($service, $spreadsheetId, $config);

            $syncFields = $config['sync_fields'] ?? [
                'no_registrasi', 'nama_lengkap', 'nisn', 'jenis_kelamin', 'sekolah_asal', 'no_hp', 'email', 'status', 'tanggal_daftar', 'custom_fields',
            ];
            $customFields = PpdbSetting::getValue('form_fields', []);
            $rowData = $this->rowBuilder->buildRowData($siswa, $syncFields, $customFields);

            if (empty($rowData)) {
                return true;
            }

            $service->spreadsheets_values->append(
                $spreadsheetId,
                $sheetName,
                new ValueRange(['values' => [$rowData]]),
                ['valueInputOption' => 'USER_ENTERED']
            );

            return true;
        } catch (Exception $e) {
            Log::error('Google Sheets Sync Failed for Student '.$siswa->id.': '.$e->getMessage());

            return false;
        }
    }

    /**
     * Clear and sync all candidates to Google Sheets (Bulk Sync).
     *
     * @return array<string, mixed>
     */
    public function syncAll(): array
    {
        $config = PpdbSetting::getValue('google_sheets', []);

        if (empty($config) || empty($config['spreadsheet_id'])) {
            return [
                'success' => false,
                'message' => 'Konfigurasi Google Sheets belum lengkap.',
            ];
        }

        try {
            $service = $this->clientFactory->make($config);
            $spreadsheetId = $config['spreadsheet_id'];

            $syncFields = $config['sync_fields'] ?? [
                'no_registrasi', 'nama_lengkap', 'nisn', 'jenis_kelamin', 'sekolah_asal', 'no_hp', 'email', 'status', 'tanggal_daftar', 'custom_fields',
            ];
            $headerStyle = $config['header_style'] ?? 'purple';
            $splitByStatus = ! empty($config['split_by_status']);
            $customFields = PpdbSetting::getValue('form_fields', []);

            $activeSheets = $config['active_sheets'] ?? ['semua', 'diterima', 'pending', 'ditolak', 'ringkasan'];
            $sheetNames = $config['sheet_names'] ?? [
                'semua' => 'Semua Pendaftar',
                'diterima' => 'Siswa Diterima',
                'pending' => 'Dalam Proses',
                'ditolak' => 'Siswa Ditolak',
                'ringkasan' => 'Ringkasan Data',
            ];

            $headers = $this->rowBuilder->buildHeaders($syncFields, $customFields);
            $columnCount = count($headers);
            $sheetMap = $this->syncManager->buildSheetMap($service, $spreadsheetId);
            $syncedTabsCount = 0;

            if ($splitByStatus) {
                // Split Mode: one tab per status
                $sheetsToManage = [
                    'semua' => ['title' => $sheetNames['semua'] ?: 'Semua Pendaftar', 'query' => PpdbSiswa::query()],
                    'diterima' => ['title' => $sheetNames['diterima'] ?: 'Siswa Diterima', 'query' => PpdbSiswa::where('status', 'diterima')],
                    'pending' => ['title' => $sheetNames['pending'] ?: 'Dalam Proses', 'query' => PpdbSiswa::where('status', 'pending')],
                    'ditolak' => ['title' => $sheetNames['ditolak'] ?: 'Siswa Ditolak', 'query' => PpdbSiswa::where('status', 'ditolak')],
                ];

                foreach ($sheetsToManage as $key => $sheetInfo) {
                    if (! in_array($key, $activeSheets)) {
                        continue;
                    }

                    $title = $sheetInfo['title'];
                    $sheetId = $this->syncManager->getOrCreateSheetId($service, $spreadsheetId, $title, $sheetMap);
                    $service->spreadsheets_values->clear($spreadsheetId, $title, new ClearValuesRequest);

                    $students = $sheetInfo['query']->orderBy('submitted_at', 'asc')->get();
                    $values = [$headers];
                    foreach ($students as $siswa) {
                        $values[] = $this->rowBuilder->buildRowData($siswa, $syncFields, $customFields);
                    }

                    $this->syncManager->writeValues($service, $spreadsheetId, "{$title}!A1", $values);

                    if ($columnCount > 0) {
                        $this->formatter->formatSheetHeaders($service, $spreadsheetId, $sheetId, $columnCount, $headerStyle);
                    }
                    $syncedTabsCount++;
                }
            } else {
                // Single Tab Mode
                if (in_array('semua', $activeSheets)) {
                    $sheetName = $sheetNames['semua'] ?? 'Semua Pendaftar';
                    $sheetId = $this->syncManager->getOrCreateSheetId($service, $spreadsheetId, $sheetName, $sheetMap);
                    $service->spreadsheets_values->clear($spreadsheetId, $sheetName, new ClearValuesRequest);

                    $students = PpdbSiswa::orderBy('submitted_at', 'asc')->get();
                    $values = [$headers];
                    foreach ($students as $siswa) {
                        $values[] = $this->rowBuilder->buildRowData($siswa, $syncFields, $customFields);
                    }

                    $this->syncManager->writeValues($service, $spreadsheetId, "{$sheetName}!A1", $values);

                    if ($columnCount > 0) {
                        $this->formatter->formatSheetHeaders($service, $spreadsheetId, $sheetId, $columnCount, $headerStyle);
                    }
                    $syncedTabsCount++;
                }
            }

            // Sync Summary Sheet Tab if active
            if (in_array('ringkasan', $activeSheets)) {
                $summaryTitle = $sheetNames['ringkasan'] ?: 'Ringkasan Data';
                $sheetId = $this->syncManager->getOrCreateSheetId($service, $spreadsheetId, $summaryTitle, $sheetMap);
                $this->formatter->syncSummarySheet($service, $spreadsheetId, $sheetId, $summaryTitle, $headerStyle);
                $syncedTabsCount++;
            }

            return [
                'success' => true,
                'message' => "Sinkronisasi berhasil! Berhasil memperbarui {$syncedTabsCount} tab lembar kerja secara profesional.",
            ];
        } catch (Exception $e) {
            Log::error('Google Sheets Bulk Sync Failed: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Sinkronisasi massal gagal: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Ensure all active sheet tabs exist and are properly formatted.
     *
     * @return array<int, string> Titles of newly created sheets.
     */
    public function ensureSheetsExist(Sheets $service, string $spreadsheetId, array $config): array
    {
        $sheetMap = $this->syncManager->buildSheetMap($service, $spreadsheetId);

        $activeSheets = $config['active_sheets'] ?? ['semua', 'diterima', 'pending', 'ditolak', 'ringkasan'];
        $sheetNames = $config['sheet_names'] ?? [
            'semua' => 'Semua Pendaftar',
            'diterima' => 'Siswa Diterima',
            'pending' => 'Dalam Proses',
            'ditolak' => 'Siswa Ditolak',
            'ringkasan' => 'Ringkasan Data',
        ];
        $syncFields = $config['sync_fields'] ?? [
            'no_registrasi', 'nama_lengkap', 'nisn', 'jenis_kelamin', 'sekolah_asal', 'no_hp', 'email', 'status', 'tanggal_daftar', 'custom_fields',
        ];
        $headerStyle = $config['header_style'] ?? 'purple';
        $customFields = PpdbSetting::getValue('form_fields', []);
        $headers = $this->rowBuilder->buildHeaders($syncFields, $customFields);
        $splitByStatus = ! empty($config['split_by_status']);

        // Determine which sheets need to be verified
        $sheetsToVerify = [];

        if ($splitByStatus) {
            foreach (['semua', 'diterima', 'pending', 'ditolak'] as $key) {
                if (in_array($key, $activeSheets)) {
                    $sheetsToVerify[$key] = $sheetNames[$key] ?: match ($key) {
                        'semua' => 'Semua Pendaftar',
                        'diterima' => 'Siswa Diterima',
                        'pending' => 'Dalam Proses',
                        'ditolak' => 'Siswa Ditolak',
                    };
                }
            }
        } else {
            if (in_array('semua', $activeSheets)) {
                $sheetsToVerify['semua'] = $sheetNames['semua'] ?: 'Semua Pendaftar';
            }
        }

        if (in_array('ringkasan', $activeSheets)) {
            $sheetsToVerify['ringkasan'] = $sheetNames['ringkasan'] ?: 'Ringkasan Data';
        }

        $createdSheets = [];

        foreach ($sheetsToVerify as $key => $title) {
            if (isset($sheetMap[$title])) {
                continue;
            }

            $sheetId = $this->syncManager->getOrCreateSheetId($service, $spreadsheetId, $title, $sheetMap);

            if ($key === 'ringkasan') {
                $this->formatter->syncSummarySheet($service, $spreadsheetId, $sheetId, $title, $headerStyle);
            } else {
                if (! empty($headers)) {
                    $this->syncManager->writeValues($service, $spreadsheetId, "{$title}!A1", [$headers]);
                    $this->formatter->formatSheetHeaders($service, $spreadsheetId, $sheetId, count($headers), $headerStyle);
                }
            }

            $createdSheets[] = $title;
        }

        return $createdSheets;
    }
}
