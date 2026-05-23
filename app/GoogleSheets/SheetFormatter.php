<?php

namespace App\GoogleSheets;

use App\Models\PpdbSiswa;
use Google\Service\Sheets as GoogleSheets;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Google\Service\Sheets\ClearValuesRequest;
use Google\Service\Sheets\Request;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\DB;

class SheetFormatter
{
    /**
     * Color palette map keyed by header style name.
     *
     * @return array<string, array<string, array<string, float>>>
     */
    private function rgbMap(): array
    {
        return [
            'purple' => ['bg' => ['red' => 79 / 255, 'green' => 69 / 255, 'blue' => 178 / 255], 'fg' => ['red' => 1.0, 'green' => 1.0, 'blue' => 1.0]],
            'emerald' => ['bg' => ['red' => 16 / 255, 'green' => 124 / 255, 'blue' => 65 / 255], 'fg' => ['red' => 1.0, 'green' => 1.0, 'blue' => 1.0]],
            'dark' => ['bg' => ['red' => 30 / 255, 'green' => 41 / 255, 'blue' => 59 / 255], 'fg' => ['red' => 1.0, 'green' => 1.0, 'blue' => 1.0]],
            'plain' => ['bg' => ['red' => 241 / 255, 'green' => 245 / 255, 'blue' => 249 / 255], 'fg' => ['red' => 0.1, 'green' => 0.1, 'blue' => 0.1]],
        ];
    }

    /**
     * Apply premium cell formatting to the header row of a student data sheet.
     */
    public function formatSheetHeaders(GoogleSheets $service, string $spreadsheetId, int $sheetId, int $columnCount, string $headerStyle): void
    {
        $style = $this->rgbMap()[$headerStyle] ?? $this->rgbMap()['purple'];

        $requests = [
            // 1. Background color, bold, centered header row
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

        $service->spreadsheets->batchUpdate(
            $spreadsheetId,
            new BatchUpdateSpreadsheetRequest(['requests' => $requests])
        );
    }

    /**
     * Clear, populate, and format the summary statistics sheet tab.
     */
    public function syncSummarySheet(GoogleSheets $service, string $spreadsheetId, int $sheetId, string $sheetTitle, string $headerStyle): void
    {
        // 1. Clear existing values
        $service->spreadsheets_values->clear($spreadsheetId, $sheetTitle, new ClearValuesRequest);

        // Gather database statistics
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

        // 2. Build data structure
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

        // 3. Write values to Summary tab
        $service->spreadsheets_values->update(
            $spreadsheetId,
            "{$sheetTitle}!A1",
            new ValueRange(['values' => $values]),
            ['valueInputOption' => 'USER_ENTERED']
        );

        // 4. Apply formatting requests
        $style = $this->rgbMap()[$headerStyle] ?? $this->rgbMap()['purple'];
        $requests = $this->buildSummaryFormatRequests($sheetId, $style, $headerStyle);

        $service->spreadsheets->batchUpdate(
            $spreadsheetId,
            new BatchUpdateSpreadsheetRequest(['requests' => $requests])
        );
    }

    /**
     * Build the list of formatting Request objects for the summary sheet.
     *
     * @param  array<string, array<string, float>>  $style
     * @return array<int, Request>
     */
    private function buildSummaryFormatRequests(int $sheetId, array $style, string $headerStyle): array
    {
        return [
            // Merge title cell (A1:C1)
            new Request([
                'mergeCells' => [
                    'range' => ['sheetId' => $sheetId, 'startRowIndex' => 0, 'endRowIndex' => 1, 'startColumnIndex' => 0, 'endColumnIndex' => 3],
                    'mergeType' => 'MERGE_ALL',
                ],
            ]),
            // Style Title (A1:C1)
            new Request([
                'repeatCell' => [
                    'range' => ['sheetId' => $sheetId, 'startRowIndex' => 0, 'endRowIndex' => 1, 'startColumnIndex' => 0, 'endColumnIndex' => 3],
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
            // Section Header — Row 4 (STATISTIK PENDAFTARAN)
            new Request([
                'repeatCell' => [
                    'range' => ['sheetId' => $sheetId, 'startRowIndex' => 3, 'endRowIndex' => 4, 'startColumnIndex' => 0, 'endColumnIndex' => 3],
                    'cell' => ['userEnteredFormat' => ['backgroundColor' => $style['bg'], 'textFormat' => ['foregroundColor' => $style['fg'], 'bold' => true]]],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat)',
                ],
            ]),
            // Section Header — Row 11 (PROFIL GENDER)
            new Request([
                'repeatCell' => [
                    'range' => ['sheetId' => $sheetId, 'startRowIndex' => 10, 'endRowIndex' => 11, 'startColumnIndex' => 0, 'endColumnIndex' => 2],
                    'cell' => ['userEnteredFormat' => ['backgroundColor' => $style['bg'], 'textFormat' => ['foregroundColor' => $style['fg'], 'bold' => true]]],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat)',
                ],
            ]),
            // Section Header — Row 16 (TOP 5 SEKOLAH)
            new Request([
                'repeatCell' => [
                    'range' => ['sheetId' => $sheetId, 'startRowIndex' => 15, 'endRowIndex' => 16, 'startColumnIndex' => 0, 'endColumnIndex' => 2],
                    'cell' => ['userEnteredFormat' => ['backgroundColor' => $style['bg'], 'textFormat' => ['foregroundColor' => $style['fg'], 'bold' => true]]],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat)',
                ],
            ]),
            // Sub-header Row 5 (Status Kelulusan / Jumlah / Persentase)
            new Request([
                'repeatCell' => [
                    'range' => ['sheetId' => $sheetId, 'startRowIndex' => 4, 'endRowIndex' => 5, 'startColumnIndex' => 0, 'endColumnIndex' => 3],
                    'cell' => ['userEnteredFormat' => ['backgroundColor' => ['red' => 0.95, 'green' => 0.95, 'blue' => 0.95], 'textFormat' => ['bold' => true], 'horizontalAlignment' => 'CENTER']],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat,horizontalAlignment)',
                ],
            ]),
            // Sub-header Row 12 (Jenis Kelamin / Jumlah)
            new Request([
                'repeatCell' => [
                    'range' => ['sheetId' => $sheetId, 'startRowIndex' => 11, 'endRowIndex' => 12, 'startColumnIndex' => 0, 'endColumnIndex' => 2],
                    'cell' => ['userEnteredFormat' => ['backgroundColor' => ['red' => 0.95, 'green' => 0.95, 'blue' => 0.95], 'textFormat' => ['bold' => true], 'horizontalAlignment' => 'CENTER']],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat,horizontalAlignment)',
                ],
            ]),
            // Sub-header Row 17 (Nama Sekolah / Jumlah)
            new Request([
                'repeatCell' => [
                    'range' => ['sheetId' => $sheetId, 'startRowIndex' => 16, 'endRowIndex' => 17, 'startColumnIndex' => 0, 'endColumnIndex' => 2],
                    'cell' => ['userEnteredFormat' => ['backgroundColor' => ['red' => 0.95, 'green' => 0.95, 'blue' => 0.95], 'textFormat' => ['bold' => true], 'horizontalAlignment' => 'CENTER']],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat,horizontalAlignment)',
                ],
            ]),
            // Bold total row (Row 9)
            new Request([
                'repeatCell' => [
                    'range' => ['sheetId' => $sheetId, 'startRowIndex' => 8, 'endRowIndex' => 9, 'startColumnIndex' => 0, 'endColumnIndex' => 3],
                    'cell' => ['userEnteredFormat' => ['textFormat' => ['bold' => true], 'backgroundColor' => ['red' => 0.98, 'green' => 0.98, 'blue' => 0.98]]],
                    'fields' => 'userEnteredFormat(textFormat,backgroundColor)',
                ],
            ]),
            // Auto-resize columns
            new Request([
                'autoResizeDimensions' => [
                    'dimensions' => ['sheetId' => $sheetId, 'dimension' => 'COLUMNS', 'startIndex' => 0, 'endIndex' => 3],
                ],
            ]),
        ];
    }
}
