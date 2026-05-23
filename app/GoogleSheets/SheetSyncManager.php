<?php

namespace App\GoogleSheets;

use Google\Service\Sheets as GoogleSheets;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Google\Service\Sheets\ValueRange;

class SheetSyncManager
{
    /**
     * Get the sheetId for an existing tab, or create a new tab and return its sheetId.
     *
     * @param  array<string, int>  $sheetMap  Mutable reference: title → sheetId map.
     */
    public function getOrCreateSheetId(GoogleSheets $service, string $spreadsheetId, string $title, array &$sheetMap): int
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
     * Fetch the spreadsheet metadata and return a title → sheetId map.
     *
     * @return array<string, int>
     */
    public function buildSheetMap(GoogleSheets $service, string $spreadsheetId): array
    {
        $spreadsheet = $service->spreadsheets->get($spreadsheetId);
        $sheetMap = [];

        foreach ($spreadsheet->getSheets() as $s) {
            $sheetMap[$s->getProperties()->getTitle()] = $s->getProperties()->getSheetId();
        }

        return $sheetMap;
    }

    /**
     * Write values to a sheet range using USER_ENTERED mode.
     *
     * @param  array<int, array<int, mixed>>  $values
     */
    public function writeValues(GoogleSheets $service, string $spreadsheetId, string $range, array $values): void
    {
        $service->spreadsheets_values->update(
            $spreadsheetId,
            $range,
            new ValueRange(['values' => $values]),
            ['valueInputOption' => 'USER_ENTERED']
        );
    }
}
