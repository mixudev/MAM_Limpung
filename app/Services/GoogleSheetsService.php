<?php

namespace App\Services;

use App\Models\PpdbSetting;
use App\Models\PpdbSiswa;
use App\Models\SecuritySetting;
use Exception;
use Google\Client as GoogleClient;
use Google\Service\Sheets as GoogleSheets;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Google\Service\Sheets\ClearValuesRequest;
use Google\Service\Sheets\Request;
use Google\Service\Sheets\ValueRange;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    /**
     * Get the authenticated Google Sheets API service.
     */
    protected function getSheetsService(array $config): GoogleSheets
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
            $httpClient = new Client([
                'verify' => false,
            ]);
            $client->setHttpClient($httpClient);
        }

        return new GoogleSheets($client);
    }

    /**
     * Test connection to the Google Spreadsheet and Sheet.
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
            $service = $this->getSheetsService($config);
            $spreadsheetId = $config['spreadsheet_id'];
            $sheetNames = $config['sheet_names'] ?? [
                'semua' => 'Semua Pendaftar',
            ];
            $sheetName = $sheetNames['semua'] ?? 'Semua Pendaftar';

            // Try to fetch spreadsheet metadata to test connection
            $spreadsheet = $service->spreadsheets->get($spreadsheetId);

            // Check if the specific sheet name exists
            $sheetExists = false;
            foreach ($spreadsheet->getSheets() as $sheet) {
                if ($sheet->getProperties()->getTitle() === $sheetName) {
                    $sheetExists = true;
                    break;
                }
            }

            if (! $sheetExists && empty($config['split_by_status'])) {
                return [
                    'success' => false,
                    'message' => "Spreadsheet berhasil diakses, namun Sheet/Tab dengan nama '{$sheetName}' tidak ditemukan.",
                ];
            }

            // Get service account email to display dynamically in the tutorial
            $decryptedJson = Crypt::decryptString($serviceAccountJson);
            $credentials = json_decode($decryptedJson, true);
            $clientEmail = $credentials['client_email'] ?? 'unknown';

            return [
                'success' => true,
                'message' => 'Koneksi berhasil! Sistem berhasil terhubung ke Google Sheets API.',
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

        // If split_by_status is enabled or a summary sheet is active, we run a full sync to keep all status tabs and counts accurate!
        if (! empty($config['split_by_status']) || in_array('ringkasan', $activeSheets)) {
            $result = $this->syncAll();

            return $result['success'];
        }

        try {
            $service = $this->getSheetsService($config);
            $spreadsheetId = $config['spreadsheet_id'];
            $sheetNames = $config['sheet_names'] ?? [
                'semua' => 'Semua Pendaftar',
            ];
            $sheetName = $sheetNames['semua'] ?? 'Semua Pendaftar';

            // Get selected fields and custom fields
            $syncFields = $config['sync_fields'] ?? [
                'no_registrasi', 'nama_lengkap', 'nisn', 'jenis_kelamin', 'sekolah_asal', 'no_hp', 'email', 'status', 'tanggal_daftar', 'custom_fields',
            ];
            $customFields = PpdbSetting::getValue('form_fields', []);

            // Build row data based on selected fields
            $rowData = $this->buildRowData($siswa, $syncFields, $customFields);

            if (empty($rowData)) {
                return true;
            }

            $body = new ValueRange([
                'values' => [$rowData],
            ]);

            $params = [
                'valueInputOption' => 'USER_ENTERED',
            ];

            $service->spreadsheets_values->append($spreadsheetId, $sheetName, $body, $params);

            return true;
        } catch (Exception $e) {
            Log::error('Google Sheets Sync Failed for Student '.$siswa->id.': '.$e->getMessage());

            return false;
        }
    }

    /**
     * Clear and sync all candidates to Google Sheets (Bulk Sync).
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
            $service = $this->getSheetsService($config);
            $spreadsheetId = $config['spreadsheet_id'];

            // Get selected fields, header style, active sheets and custom sheet names
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

            // Build headers
            $headers = $this->buildHeaders($syncFields, $customFields);
            $columnCount = count($headers);

            // Fetch spreadsheet metadata to get existing sheets and map title -> sheetId
            $spreadsheet = $service->spreadsheets->get($spreadsheetId);
            $sheetMap = [];
            foreach ($spreadsheet->getSheets() as $s) {
                $sheetMap[$s->getProperties()->getTitle()] = $s->getProperties()->getSheetId();
            }

            $syncedTabsCount = 0;

            if ($splitByStatus) {
                // Split Mode: Sync to distinct tabs based on status
                $sheetsToManage = [
                    'semua' => ['title' => $sheetNames['semua'] ?: 'Semua Pendaftar', 'query' => PpdbSiswa::query()],
                    'diterima' => ['title' => $sheetNames['diterima'] ?: 'Siswa Diterima', 'query' => PpdbSiswa::where('status', 'diterima')],
                    'pending' => ['title' => $sheetNames['pending'] ?: 'Dalam Proses', 'query' => PpdbSiswa::where('status', 'pending')],
                    'ditolak' => ['title' => $sheetNames['ditolak'] ?: 'Siswa Ditolak', 'query' => PpdbSiswa::where('status', 'ditolak')],
                ];

                foreach ($sheetsToManage as $key => $sheetInfo) {
                    if (! in_array($key, $activeSheets)) {
                        continue; // Skip disabled sheets
                    }

                    $title = $sheetInfo['title'];
                    $query = $sheetInfo['query'];

                    // 1. Get or Create Sheet Tab
                    $sheetId = $this->getOrCreateSheetId($service, $spreadsheetId, $title, $sheetMap);

                    // 2. Clear existing values
                    $service->spreadsheets_values->clear($spreadsheetId, $title, new ClearValuesRequest);

                    // 3. Retrieve students and format rows
                    $students = $query->orderBy('submitted_at', 'asc')->get();
                    $values = [$headers];
                    foreach ($students as $siswa) {
                        $values[] = $this->buildRowData($siswa, $syncFields, $customFields);
                    }

                    // 4. Write new values
                    $body = new ValueRange(['values' => $values]);
                    $service->spreadsheets_values->update($spreadsheetId, "{$title}!A1", $body, [
                        'valueInputOption' => 'USER_ENTERED',
                    ]);

                    // 5. Apply premium formatting (colors, freeze row, auto-resize columns)
                    if ($columnCount > 0) {
                        $this->formatSheetHeaders($service, $spreadsheetId, $sheetId, $columnCount, $headerStyle);
                    }
                    $syncedTabsCount++;
                }
            } else {
                // Single Tab Mode
                if (in_array('semua', $activeSheets)) {
                    $sheetName = $sheetNames['semua'] ?? 'Semua Pendaftar';
                    $sheetId = $this->getOrCreateSheetId($service, $spreadsheetId, $sheetName, $sheetMap);

                    $service->spreadsheets_values->clear($spreadsheetId, $sheetName, new ClearValuesRequest);

                    $students = PpdbSiswa::orderBy('submitted_at', 'asc')->get();
                    $values = [$headers];
                    foreach ($students as $siswa) {
                        $values[] = $this->buildRowData($siswa, $syncFields, $customFields);
                    }

                    $body = new ValueRange(['values' => $values]);
                    $service->spreadsheets_values->update($spreadsheetId, "{$sheetName}!A1", $body, [
                        'valueInputOption' => 'USER_ENTERED',
                    ]);

                    if ($columnCount > 0) {
                        $this->formatSheetHeaders($service, $spreadsheetId, $sheetId, $columnCount, $headerStyle);
                    }
                    $syncedTabsCount++;
                }
            }

            // Sync Summary Sheet Tab if active
            if (in_array('ringkasan', $activeSheets)) {
                $summaryTitle = $sheetNames['ringkasan'] ?: 'Ringkasan Data';
                $sheetId = $this->getOrCreateSheetId($service, $spreadsheetId, $summaryTitle, $sheetMap);

                $this->syncSummarySheet($service, $spreadsheetId, $sheetId, $summaryTitle, $headerStyle);
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
     * Get sheetId if exists, or create new sheet dynamically.
     */
    protected function getOrCreateSheetId($service, string $spreadsheetId, string $title, array &$sheetMap): int
    {
        if (isset($sheetMap[$title])) {
            return $sheetMap[$title];
        }

        $addSheetRequest = new BatchUpdateSpreadsheetRequest([
            'requests' => [
                'addSheet' => [
                    'properties' => [
                        'title' => $title,
                    ],
                ],
            ],
        ]);
        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $addSheetRequest);
        $sheetId = $response->getReplies()[0]->getAddSheet()->getProperties()->getSheetId();
        $sheetMap[$title] = $sheetId;

        return $sheetId;
    }

    /**
     * Generate and format the beautifully designed Summary tab sheet.
     */
    protected function syncSummarySheet($service, string $spreadsheetId, int $sheetId, string $sheetTitle, string $headerStyle): void
    {
        // 1. Clear existing values
        $service->spreadsheets_values->clear($spreadsheetId, $sheetTitle, new ClearValuesRequest);

        // Get database statistics
        $total = PpdbSiswa::count();
        $diterima = PpdbSiswa::where('status', 'diterima')->count();
        $pending = PpdbSiswa::where('status', 'pending')->count();
        $ditolak = PpdbSiswa::where('status', 'ditolak')->count();

        $male = PpdbSiswa::where('jenis_kelamin', 'L')->count();
        $female = PpdbSiswa::where('jenis_kelamin', 'P')->count();

        $pctDiterima = $total > 0 ? round(($diterima / $total) * 100, 1).'%' : '0%';
        $pctPending = $total > 0 ? round(($pending / $total) * 100, 1).'%' : '0%';
        $pctDitolak = $total > 0 ? round(($ditolak / $total) * 100, 1).'%' : '0%';

        $topSchools = PpdbSiswa::select('sekolah_asal', DB::raw('count(*) as total'))
            ->groupBy('sekolah_asal')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Build data structure
        $values = [
            ['RINGKASAN DATA PPDB MAM LIMPUNG'],
            ['Terakhir Diperbarui: '.date('d-m-Y H:i:s')],
            [],
            ['STATISTIK PENDAFTARAN', '', ''],
            ['Status Kelulusan', 'Jumlah Siswa', 'Persentase'],
            ['Siswa Diterima', $diterima, $pctDiterima],
            ['Dalam Proses (Seleksi)', $pending, $pctPending],
            ['Siswa Ditolak', $ditolak, $pctDitolak],
            ['TOTAL PENDAFTAR', $total, '100%'],
            [],
            ['PROFIL GENDER PENDAFTAR', ''],
            ['Jenis Kelamin', 'Jumlah Siswa'],
            ['Laki-laki', $male],
            ['Perempuan', $female],
            [],
            ['TOP 5 SEKOLAH ASAL PENDAFTAR', ''],
            ['Nama Sekolah Asal', 'Jumlah Calon Siswa'],
        ];

        foreach ($topSchools as $school) {
            $values[] = [$school->sekolah_asal ?: 'Tidak Diketahui', $school->total];
        }

        // Write values to Summary tab
        $body = new ValueRange(['values' => $values]);
        $service->spreadsheets_values->update($spreadsheetId, "{$sheetTitle}!A1", $body, [
            'valueInputOption' => 'USER_ENTERED',
        ]);

        // Style the Summary sheet using Google Sheets formatting API
        $rgbMap = [
            'purple' => ['bg' => ['red' => 79 / 255, 'green' => 69 / 255, 'blue' => 178 / 255], 'fg' => ['red' => 1.0, 'green' => 1.0, 'blue' => 1.0]],
            'emerald' => ['bg' => ['red' => 16 / 255, 'green' => 124 / 255, 'blue' => 65 / 255], 'fg' => ['red' => 1.0, 'green' => 1.0, 'blue' => 1.0]],
            'dark' => ['bg' => ['red' => 30 / 255, 'green' => 41 / 255, 'blue' => 59 / 255], 'fg' => ['red' => 1.0, 'green' => 1.0, 'blue' => 1.0]],
            'plain' => ['bg' => ['red' => 241 / 255, 'green' => 245 / 255, 'blue' => 249 / 255], 'fg' => ['red' => 0.1, 'green' => 0.1, 'blue' => 0.1]],
        ];
        $style = $rgbMap[$headerStyle] ?? $rgbMap['purple'];

        $requests = [
            // Merge title cell (A1:C1)
            new Request([
                'mergeCells' => [
                    'range' => [
                        'sheetId' => $sheetId,
                        'startRowIndex' => 0,
                        'endRowIndex' => 1,
                        'startColumnIndex' => 0,
                        'endColumnIndex' => 3,
                    ],
                    'mergeType' => 'MERGE_ALL',
                ],
            ]),
            // Style Title (A1:C1)
            new Request([
                'repeatCell' => [
                    'range' => [
                        'sheetId' => $sheetId,
                        'startRowIndex' => 0,
                        'endRowIndex' => 1,
                        'startColumnIndex' => 0,
                        'endColumnIndex' => 3,
                    ],
                    'cell' => [
                        'userEnteredFormat' => [
                            'textFormat' => [
                                'bold' => true,
                                'fontSize' => 14,
                                'foregroundColor' => $headerStyle === 'plain' ? ['red' => 0.1, 'green' => 0.1, 'blue' => 0.1] : $style['bg'],
                            ],
                            'horizontalAlignment' => 'CENTER',
                        ],
                    ],
                    'fields' => 'userEnteredFormat(textFormat,horizontalAlignment)',
                ],
            ]),
            // Format Section Headers (Row 4, Row 11, Row 16)
            new Request([
                'repeatCell' => [
                    'range' => [
                        'sheetId' => $sheetId,
                        'startRowIndex' => 3,
                        'endRowIndex' => 4,
                        'startColumnIndex' => 0,
                        'endColumnIndex' => 3,
                    ],
                    'cell' => [
                        'userEnteredFormat' => [
                            'backgroundColor' => $style['bg'],
                            'textFormat' => [
                                'foregroundColor' => $style['fg'],
                                'bold' => true,
                            ],
                        ],
                    ],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat)',
                ],
            ]),
            new Request([
                'repeatCell' => [
                    'range' => [
                        'sheetId' => $sheetId,
                        'startRowIndex' => 10,
                        'endRowIndex' => 11,
                        'startColumnIndex' => 0,
                        'endColumnIndex' => 2,
                    ],
                    'cell' => [
                        'userEnteredFormat' => [
                            'backgroundColor' => $style['bg'],
                            'textFormat' => [
                                'foregroundColor' => $style['fg'],
                                'bold' => true,
                            ],
                        ],
                    ],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat)',
                ],
            ]),
            new Request([
                'repeatCell' => [
                    'range' => [
                        'sheetId' => $sheetId,
                        'startRowIndex' => 15,
                        'endRowIndex' => 16,
                        'startColumnIndex' => 0,
                        'endColumnIndex' => 2,
                    ],
                    'cell' => [
                        'userEnteredFormat' => [
                            'backgroundColor' => $style['bg'],
                            'textFormat' => [
                                'foregroundColor' => $style['fg'],
                                'bold' => true,
                            ],
                        ],
                    ],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat)',
                ],
            ]),
            // Format Table Sub-headers (Row 5, Row 12, Row 17)
            new Request([
                'repeatCell' => [
                    'range' => [
                        'sheetId' => $sheetId,
                        'startRowIndex' => 4,
                        'endRowIndex' => 5,
                        'startColumnIndex' => 0,
                        'endColumnIndex' => 3,
                    ],
                    'cell' => [
                        'userEnteredFormat' => [
                            'backgroundColor' => ['red' => 0.95, 'green' => 0.95, 'blue' => 0.95],
                            'textFormat' => [
                                'bold' => true,
                            ],
                            'horizontalAlignment' => 'CENTER',
                        ],
                    ],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat,horizontalAlignment)',
                ],
            ]),
            new Request([
                'repeatCell' => [
                    'range' => [
                        'sheetId' => $sheetId,
                        'startRowIndex' => 11,
                        'endRowIndex' => 12,
                        'startColumnIndex' => 0,
                        'endColumnIndex' => 2,
                    ],
                    'cell' => [
                        'userEnteredFormat' => [
                            'backgroundColor' => ['red' => 0.95, 'green' => 0.95, 'blue' => 0.95],
                            'textFormat' => [
                                'bold' => true,
                            ],
                            'horizontalAlignment' => 'CENTER',
                        ],
                    ],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat,horizontalAlignment)',
                ],
            ]),
            new Request([
                'repeatCell' => [
                    'range' => [
                        'sheetId' => $sheetId,
                        'startRowIndex' => 16,
                        'endRowIndex' => 17,
                        'startColumnIndex' => 0,
                        'endColumnIndex' => 2,
                    ],
                    'cell' => [
                        'userEnteredFormat' => [
                            'backgroundColor' => ['red' => 0.95, 'green' => 0.95, 'blue' => 0.95],
                            'textFormat' => [
                                'bold' => true,
                            ],
                            'horizontalAlignment' => 'CENTER',
                        ],
                    ],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat,horizontalAlignment)',
                ],
            ]),
            // Format Total Summary Row (Row 9)
            new Request([
                'repeatCell' => [
                    'range' => [
                        'sheetId' => $sheetId,
                        'startRowIndex' => 8,
                        'endRowIndex' => 9,
                        'startColumnIndex' => 0,
                        'endColumnIndex' => 3,
                    ],
                    'cell' => [
                        'userEnteredFormat' => [
                            'textFormat' => [
                                'bold' => true,
                            ],
                            'backgroundColor' => ['red' => 0.98, 'green' => 0.98, 'blue' => 0.98],
                        ],
                    ],
                    'fields' => 'userEnteredFormat(textFormat,backgroundColor)',
                ],
            ]),
            // Auto resize columns on summary sheet
            new Request([
                'autoResizeDimensions' => [
                    'dimensions' => [
                        'sheetId' => $sheetId,
                        'dimension' => 'COLUMNS',
                        'startIndex' => 0,
                        'endIndex' => 3,
                    ],
                ],
            ]),
        ];

        $batchUpdateRequest = new BatchUpdateSpreadsheetRequest([
            'requests' => $requests,
        ]);

        $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
    }

    /**
     * Build row values array based on selected fields.
     */
    protected function buildRowData(PpdbSiswa $siswa, array $syncFields, array $customFields): array
    {
        $rowData = [];

        if (in_array('no_registrasi', $syncFields)) {
            $rowData[] = $siswa->nomor_registrasi;
        }
        if (in_array('nama_lengkap', $syncFields)) {
            $rowData[] = $siswa->nama_lengkap;
        }
        if (in_array('nisn', $syncFields)) {
            $rowData[] = $siswa->nisn;
        }
        if (in_array('jenis_kelamin', $syncFields)) {
            $rowData[] = $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
        }
        if (in_array('sekolah_asal', $syncFields)) {
            $rowData[] = $siswa->sekolah_asal;
        }
        if (in_array('no_hp', $syncFields)) {
            $rowData[] = $siswa->nomor_hp;
        }
        if (in_array('email', $syncFields)) {
            $rowData[] = $siswa->email ?? '-';
        }
        if (in_array('status', $syncFields)) {
            $rowData[] = strtoupper($siswa->status ?? 'PENDING');
        }
        if (in_array('tanggal_daftar', $syncFields)) {
            $rowData[] = $siswa->submitted_at?->format('d-m-Y H:i') ?? '-';
        }

        // Add custom fields
        if (in_array('custom_fields', $syncFields) && ! empty($customFields)) {
            foreach ($customFields as $field) {
                $val = $siswa->additional_fields[$field['id']] ?? '';
                if (is_array($val)) {
                    $val = implode(', ', $val);
                }
                $rowData[] = $val;
            }
        }

        return $rowData;
    }

    /**
     * Build headers array based on selected fields.
     */
    protected function buildHeaders(array $syncFields, array $customFields): array
    {
        $headers = [];

        if (in_array('no_registrasi', $syncFields)) {
            $headers[] = 'No. Registrasi';
        }
        if (in_array('nama_lengkap', $syncFields)) {
            $headers[] = 'Nama Lengkap';
        }
        if (in_array('nisn', $syncFields)) {
            $headers[] = 'NISN';
        }
        if (in_array('jenis_kelamin', $syncFields)) {
            $headers[] = 'Jenis Kelamin';
        }
        if (in_array('sekolah_asal', $syncFields)) {
            $headers[] = 'Sekolah Asal';
        }
        if (in_array('no_hp', $syncFields)) {
            $headers[] = 'No. HP / WA';
        }
        if (in_array('email', $syncFields)) {
            $headers[] = 'Email';
        }
        if (in_array('status', $syncFields)) {
            $headers[] = 'Status Seleksi';
        }
        if (in_array('tanggal_daftar', $syncFields)) {
            $headers[] = 'Tanggal Daftar';
        }

        // Add custom fields
        if (in_array('custom_fields', $syncFields) && ! empty($customFields)) {
            foreach ($customFields as $field) {
                $headers[] = $field['id'] === 'nama_wali' ? 'Nama Wali' : $field['label'];
            }
        }

        return $headers;
    }

    /**
     * Apply premium cell formatting using Google Sheets Batch Update.
     */
    protected function formatSheetHeaders($service, string $spreadsheetId, int $sheetId, int $columnCount, string $headerStyle): void
    {
        $rgbMap = [
            'purple' => ['bg' => ['red' => 79 / 255, 'green' => 69 / 255, 'blue' => 178 / 255], 'fg' => ['red' => 1.0, 'green' => 1.0, 'blue' => 1.0]],
            'emerald' => ['bg' => ['red' => 16 / 255, 'green' => 124 / 255, 'blue' => 65 / 255], 'fg' => ['red' => 1.0, 'green' => 1.0, 'blue' => 1.0]],
            'dark' => ['bg' => ['red' => 30 / 255, 'green' => 41 / 255, 'blue' => 59 / 255], 'fg' => ['red' => 1.0, 'green' => 1.0, 'blue' => 1.0]],
            'plain' => ['bg' => ['red' => 241 / 255, 'green' => 245 / 255, 'blue' => 249 / 255], 'fg' => ['red' => 0.1, 'green' => 0.1, 'blue' => 0.1]],
        ];

        $style = $rgbMap[$headerStyle] ?? $rgbMap['purple'];

        $requests = [
            // 1. Repeat cell formatting (background color, text color, bold, center aligned)
            new Request([
                'repeatCell' => [
                    'range' => [
                        'sheetId' => $sheetId,
                        'startRowIndex' => 0,
                        'endRowIndex' => 1,
                        'startColumnIndex' => 0,
                        'endColumnIndex' => $columnCount,
                    ],
                    'cell' => [
                        'userEnteredFormat' => [
                            'backgroundColor' => $style['bg'],
                            'textFormat' => [
                                'foregroundColor' => $style['fg'],
                                'bold' => true,
                                'fontSize' => 11,
                                'fontFamily' => 'Roboto',
                            ],
                            'horizontalAlignment' => 'CENTER',
                            'verticalAlignment' => 'MIDDLE',
                        ],
                    ],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat,horizontalAlignment,verticalAlignment)',
                ],
            ]),
            // 2. Freeze the first row so it stays locked on scroll
            new Request([
                'updateSheetProperties' => [
                    'properties' => [
                        'sheetId' => $sheetId,
                        'gridProperties' => [
                            'frozenRowCount' => 1,
                        ],
                    ],
                    'fields' => 'gridProperties.frozenRowCount',
                ],
            ]),
            // 3. Auto-resize all columns to fit content perfectly
            new Request([
                'autoResizeDimensions' => [
                    'dimensions' => [
                        'sheetId' => $sheetId,
                        'dimension' => 'COLUMNS',
                        'startIndex' => 0,
                        'endIndex' => $columnCount,
                    ],
                ],
            ]),
        ];

        $batchUpdateRequest = new BatchUpdateSpreadsheetRequest([
            'requests' => $requests,
        ]);

        $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
    }
}
