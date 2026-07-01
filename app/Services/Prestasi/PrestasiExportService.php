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
    public function exportExcel(Collection $achievements): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'prestasi_export_').'.xlsx';

        $headers = [
            'NO',
            'PERAIH',
            'KELAS',
            'JUDUL PRESTASI',
            'TANGGAL',
            'TINGKAT',
            'PENYELENGGARA',
        ];

        $lengths = [];
        foreach ($headers as $idx => $header) {
            $lengths[$idx + 1] = strlen($header);
        }

        foreach ($achievements as $achievement) {
            $rowValues = [
                '',
                $achievement->peraih,
                $achievement->kelas ?? '-',
                $achievement->judul,
                $achievement->tanggal_prestasi?->format('d-m-Y') ?? '-',
                $achievement->tingkatLabel(),
                $achievement->penyelenggara ?? '-',
            ];

            foreach ($rowValues as $idx => $val) {
                $colIdx = $idx + 1;
                $lengths[$colIdx] = max($lengths[$colIdx], strlen((string) $val));
            }
        }

        $options = new Options;
        $totalCols = count($headers);
        $options->mergeCells(0, 1, $totalCols - 1, 1, 0);
        $options->mergeCells(0, 2, $totalCols - 1, 2, 0);

        $writer = new Writer($options);
        $writer->openToFile($tempFile);

        $sheet = $writer->getCurrentSheet();
        $sheet->setName('Data Prestasi');

        foreach ($lengths as $colIdx => $maxLength) {
            $width = max(min($maxLength + 4, 50), 8);
            $sheet->setColumnWidth($width, $colIdx);
        }

        $thinBorder = new Border(
            new BorderPart(BorderName::BOTTOM, 'CCCCCC', BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::TOP, 'CCCCCC', BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::LEFT, 'CCCCCC', BorderWidth::THIN, BorderStyle::SOLID),
            new BorderPart(BorderName::RIGHT, 'CCCCCC', BorderWidth::THIN, BorderStyle::SOLID)
        );

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
            ->withBackgroundColor('4F45B2')
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

        $writer->addRow(new Row([
            Cell::fromValue('DAFTAR PRESTASI SISWA & TIM MA MUHAMMADIYAH LIMPUNG', $titleStyle),
        ]));

        $writer->addRow(new Row([
            Cell::fromValue('Tanggal Unduh: '.date('d-m-Y H:i:s').' | Total Data: '.count($achievements), $subTitleStyle),
        ]));

        $writer->addRow(new Row([]));

        $headerCells = [];
        foreach ($headers as $h) {
            $headerCells[] = Cell::fromValue($h, $headerStyle);
        }
        $writer->addRow(new Row($headerCells));

        foreach ($achievements as $index => $achievement) {
            $rowCells = [
                Cell::fromValue($index + 1, $dataStyleCenter),
                Cell::fromValue(strtoupper($achievement->peraih), $dataStyleLeft),
                Cell::fromValue($achievement->kelas ?? '-', $dataStyleCenter),
                Cell::fromValue($achievement->judul, $dataStyleLeft),
                Cell::fromValue($achievement->tanggal_prestasi?->format('d-m-Y') ?? '-', $dataStyleCenter),
                Cell::fromValue($achievement->tingkatLabel(), $dataStyleCenter),
                Cell::fromValue($achievement->penyelenggara ?? '-', $dataStyleLeft),
            ];
            $writer->addRow(new Row($rowCells));
        }

        $writer->close();

        return $tempFile;
    }

    public function generateTemplate(): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'prestasi_template_').'.xlsx';

        $headers = [
            'NO',
            'PERAIH',
            'KELAS',
            'JUDUL PRESTASI',
            'TANGGAL',
            'TINGKAT',
            'PENYELENGGARA',
        ];

        $options = new Options;
        $totalCols = count($headers);
        $options->mergeCells(0, 1, $totalCols - 1, 1, 0);
        $options->mergeCells(0, 2, $totalCols - 1, 2, 0);

        $writer = new Writer($options);
        $writer->openToFile($tempFile);

        $sheet = $writer->getCurrentSheet();
        $sheet->setName('Template Import Prestasi');

        $widths = [6, 28, 12, 36, 14, 16, 28];
        foreach ($widths as $i => $w) {
            $sheet->setColumnWidth($w, $i + 1);
        }

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

        $writer->addRow(new Row([
            Cell::fromValue('TEMPLATE IMPORT DATA PRESTASI - MA MUHAMMADIYAH LIMPUNG', $titleStyle),
        ]));

        $writer->addRow(new Row([
            Cell::fromValue(
                'PETUNJUK: Isi data mulai BARIS KE-5. '
                .'TINGKAT: Sekolah | Kabupaten | Kwarda | Provinsi | Nasional | Internasional | Umum. '
                .'TANGGAL: YYYY-MM-DD (contoh: 2025-06-15).',
                $infoStyle
            ),
        ]));

        $writer->addRow(new Row([]));

        $headerCells = [];
        foreach ($headers as $h) {
            $headerCells[] = Cell::fromValue($h, $headerStyle);
        }
        $writer->addRow(new Row($headerCells));

        $exampleStyle = (new Style)
            ->withFontSize(10)
            ->withFontName('Arial')
            ->withBorder($thinBorder)
            ->withCellAlignment(CellAlignment::LEFT)
            ->withBackgroundColor('F0F9FF');

        $exampleCells = [
            Cell::fromValue('1', $exampleStyle->withCellAlignment(CellAlignment::CENTER)),
            Cell::fromValue('Ahmad Fauzan', $exampleStyle),
            Cell::fromValue('XI A', $exampleStyle->withCellAlignment(CellAlignment::CENTER)),
            Cell::fromValue('Juara 1 Olimpiade Matematika', $exampleStyle),
            Cell::fromValue('2025-06-15', $exampleStyle->withCellAlignment(CellAlignment::CENTER)),
            Cell::fromValue('Provinsi', $exampleStyle->withCellAlignment(CellAlignment::CENTER)),
            Cell::fromValue('Dinas Pendidikan Jawa Tengah', $exampleStyle),
        ];
        $writer->addRow(new Row($exampleCells));

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
