<?php

namespace App\Services\Prestasi;

use Illuminate\Support\Collection;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderName;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\BorderStyle;
use OpenSpout\Common\Entity\Style\BorderWidth;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Writer\XLSX\Writer;

class PrestasiExportService
{
    /**
     * Generate Excel file for Achievements and return the temp file path.
     */
    public function exportExcel(Collection $achievements): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'prestasi_export_').'.xlsx';

        // Column labels
        $headers = [
            'NO',
            'TANGGAL',
            'TAHUN',
            'PERAIH (SISWA/TIM)',
            'JUDUL PRESTASI',
            'JUARA',
            'TINGKAT',
            'JENIS',
            'PENYELENGGARA',
            'UNGGULAN',
            'DESKRIPSI',
        ];

        // Determine column widths
        $lengths = [];
        foreach ($headers as $idx => $header) {
            $lengths[$idx + 1] = strlen($header);
        }

        foreach ($achievements as $achievement) {
            $rowValues = [
                '', // NO
                $achievement->tanggal_prestasi?->format('d-m-Y') ?? '-',
                $achievement->tahun,
                $achievement->peraih,
                $achievement->judul,
                $achievement->juara ?? '-',
                $achievement->tingkatLabel(),
                $achievement->jenis === 'akademik' ? 'Akademik' : 'Non-Akademik',
                $achievement->penyelenggara ?? '-',
                $achievement->is_featured ? 'Ya' : 'Tidak',
                strip_tags($achievement->deskripsi ?? '-'),
            ];

            foreach ($rowValues as $idx => $val) {
                $colIdx = $idx + 1;
                $lengths[$colIdx] = max($lengths[$colIdx], strlen((string) $val));
            }
        }

        $options = new Options;
        // Merge cells for title block
        $totalCols = count($headers);
        $options->mergeCells(0, 1, $totalCols - 1, 1, 0);
        $options->mergeCells(0, 2, $totalCols - 1, 2, 0);

        $writer = new Writer($options);
        $writer->openToFile($tempFile);

        $sheet = $writer->getCurrentSheet();
        $sheet->setName('Data Prestasi');

        // Apply widths
        foreach ($lengths as $colIdx => $maxLength) {
            $width = max(min($maxLength + 4, 50), 8);
            $sheet->setColumnWidth($width, $colIdx);
        }

        // Define borders
        $thinBorder = new Border(
            new BorderPart(BorderName::BOTTOM, 'CCCCCC', BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::TOP, 'CCCCCC', BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::LEFT, 'CCCCCC', BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::RIGHT, 'CCCCCC', BorderWidth::THIN, BorderStyle::SOLID)
        );

        // Styles
        $titleStyle = (new Style)
            ->withFontBold(true)
            ->withFontSize(14)
            ->withFontName('Arial')
            ->withCellAlignment(CellAlignment::CENTER);

        $subTitleStyle = (new Style)
            ->withFontSize(10)
            ->withFontItalic(true)
            ->withFontName('Arial')
            ->withFontColor('555555')
            ->withCellAlignment(CellAlignment::CENTER);

        $headerStyle = (new Style)
            ->withFontBold(true)
            ->withFontSize(10)
            ->withFontName('Arial')
            ->withFontColor('FFFFFF')
            ->withBackgroundColor('4F45B2') // MAM Limpung Signature Accent
            ->withCellAlignment(CellAlignment::CENTER)
            ->withBorder($thinBorder);

        $dataStyleLeft = (new Style)
            ->withFontSize(10)
            ->withFontName('Arial')
            ->withBorder($thinBorder)
            ->withCellAlignment(CellAlignment::LEFT);

        $dataStyleCenter = (new Style)
            ->withFontSize(10)
            ->withFontName('Arial')
            ->withBorder($thinBorder)
            ->withCellAlignment(CellAlignment::CENTER);

        // Title Block
        $writer->addRow(new Row([
            Cell::fromValue('DAFTAR PRESTASI SISWA & TIM MA MUHAMMADIYAH LIMPUNG', $titleStyle),
        ]));

        $writer->addRow(new Row([
            Cell::fromValue('Tanggal Unduh: '.date('d-m-Y H:i:s').' | Total Data: '.count($achievements), $subTitleStyle),
        ]));

        $writer->addRow(new Row([])); // Spacing

        // Headers row
        $headerCells = [];
        foreach ($headers as $h) {
            $headerCells[] = Cell::fromValue($h, $headerStyle);
        }
        $writer->addRow(new Row($headerCells));

        // Data rows
        foreach ($achievements as $index => $achievement) {
            $rowCells = [
                Cell::fromValue($index + 1, $dataStyleCenter),
                Cell::fromValue($achievement->tanggal_prestasi?->format('d-m-Y') ?? '-', $dataStyleCenter),
                Cell::fromValue($achievement->tahun, $dataStyleCenter),
                Cell::fromValue(strtoupper($achievement->peraih), $dataStyleLeft),
                Cell::fromValue($achievement->judul, $dataStyleLeft),
                Cell::fromValue($achievement->juara ?? '-', $dataStyleCenter),
                Cell::fromValue($achievement->tingkatLabel(), $dataStyleCenter),
                Cell::fromValue($achievement->jenis === 'akademik' ? 'Akademik' : 'Non-Akademik', $dataStyleCenter),
                Cell::fromValue($achievement->penyelenggara ?? '-', $dataStyleLeft),
                Cell::fromValue($achievement->is_featured ? 'Ya' : 'Tidak', $dataStyleCenter),
                Cell::fromValue(strip_tags($achievement->deskripsi ?? '-'), $dataStyleLeft),
            ];
            $writer->addRow(new Row($rowCells));
        }

        $writer->close();

        return $tempFile;
    }

    /**
     * Generate a blank Excel template for achievement import.
     *
     * @return string Temp file path
     */
    public function generateTemplate(): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'prestasi_template_').'.xlsx';

        $headers = [
            'NO',
            'TANGGAL',
            'TAHUN',
            'PERAIH (SISWA/TIM)',
            'JUDUL PRESTASI',
            'JUARA',
            'TINGKAT',
            'JENIS',
            'PENYELENGGARA',
            'UNGGULAN',
            'DESKRIPSI',
        ];

        $options = new Options;
        $totalCols = count($headers);
        $options->mergeCells(0, 1, $totalCols - 1, 1, 0);
        $options->mergeCells(0, 2, $totalCols - 1, 2, 0);

        $writer = new Writer($options);
        $writer->openToFile($tempFile);

        $sheet = $writer->getCurrentSheet();
        $sheet->setName('Template Import Prestasi');

        // Column widths
        $widths = [6, 14, 8, 28, 36, 16, 16, 16, 28, 12, 40];
        foreach ($widths as $i => $w) {
            $sheet->setColumnWidth($w, $i + 1);
        }

        // Borders
        $thinBorder = new Border(
            new BorderPart(BorderName::BOTTOM, 'CCCCCC', BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::TOP, 'CCCCCC', BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::LEFT, 'CCCCCC', BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::RIGHT, 'CCCCCC', BorderWidth::THIN, BorderStyle::SOLID)
        );

        $titleStyle = (new Style)
            ->withFontBold(true)
            ->withFontSize(13)
            ->withFontName('Arial')
            ->withCellAlignment(CellAlignment::CENTER);

        $infoStyle = (new Style)
            ->withFontSize(9)
            ->withFontItalic(true)
            ->withFontName('Arial')
            ->withFontColor('333333')
            ->withBackgroundColor('EEF2FF')
            ->withCellAlignment(CellAlignment::LEFT);

        $headerStyle = (new Style)
            ->withFontBold(true)
            ->withFontSize(10)
            ->withFontName('Arial')
            ->withFontColor('FFFFFF')
            ->withBackgroundColor('4F45B2')
            ->withCellAlignment(CellAlignment::CENTER)
            ->withBorder($thinBorder);

        $dataStyle = (new Style)
            ->withFontSize(10)
            ->withFontName('Arial')
            ->withBorder($thinBorder)
            ->withCellAlignment(CellAlignment::LEFT);

        // Row 1: Title
        $writer->addRow(new Row([
            Cell::fromValue('TEMPLATE IMPORT DATA PRESTASI - MA MUHAMMADIYAH LIMPUNG', $titleStyle),
        ]));

        // Row 2: Instructions (importer skips rows 1-4, data starts at row 5)
        $writer->addRow(new Row([
            Cell::fromValue(
                'PETUNJUK: Isi data mulai BARIS KE-5. '
                .'TINGKAT: Sekolah | Kabupaten | Provinsi | Nasional | Internasional. '
                .'JENIS: Akademik | Non-Akademik. '
                .'UNGGULAN: Ya | Tidak. '
                .'TANGGAL: YYYY-MM-DD (contoh: 2025-06-15). TAHUN: angka (contoh: 2025).',
                $infoStyle
            ),
        ]));

        // Row 3: Empty spacing
        $writer->addRow(new Row([]));

        // Row 4: Column headers
        $headerCells = [];
        foreach ($headers as $h) {
            $headerCells[] = Cell::fromValue($h, $headerStyle);
        }
        $writer->addRow(new Row($headerCells));

        // Row 5: EXAMPLE DATA (users can see the format)
        $exampleStyle = (new Style)
            ->withFontSize(10)
            ->withFontName('Arial')
            ->withBorder($thinBorder)
            ->withCellAlignment(CellAlignment::LEFT)
            ->withBackgroundColor('F0F9FF'); // Light blue background to indicate it's an example

        $exampleCells = [
            Cell::fromValue('1', $exampleStyle->withCellAlignment(CellAlignment::CENTER)),
            Cell::fromValue('2025-06-15', $exampleStyle->withCellAlignment(CellAlignment::CENTER)),
            Cell::fromValue('2025', $exampleStyle->withCellAlignment(CellAlignment::CENTER)),
            Cell::fromValue('Ahmad Fauzan', $exampleStyle),
            Cell::fromValue('Juara 1 Olimpiade Matematika', $exampleStyle),
            Cell::fromValue('Juara 1', $exampleStyle->withCellAlignment(CellAlignment::CENTER)),
            Cell::fromValue('Provinsi', $exampleStyle->withCellAlignment(CellAlignment::CENTER)),
            Cell::fromValue('Akademik', $exampleStyle->withCellAlignment(CellAlignment::CENTER)),
            Cell::fromValue('Dinas Pendidikan Jawa Tengah', $exampleStyle),
            Cell::fromValue('Ya', $exampleStyle->withCellAlignment(CellAlignment::CENTER)),
            Cell::fromValue('Olimpiade Sains Nasional tingkat Provinsi Jawa Tengah 2025', $exampleStyle),
        ];
        $writer->addRow(new Row($exampleCells));

        // Rows 6-14: 9 blank data rows ready to fill
        for ($i = 0; $i < 9; $i++) {
            $blankCells = [];
            foreach ($headers as $h) {
                $blankCells[] = Cell::fromValue('', $dataStyle);
            }
            $writer->addRow(new Row($blankCells));
        }

        $writer->close();

        return $tempFile;
    }
}
