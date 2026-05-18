<?php

namespace App\Actions\Ppdb;

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

class ExportPpdbExcelAction
{
    /**
     * Execute the Excel styled generation.
     *
     * @return string Absolute file path of the generated temp XLSX
     */
    public function execute(Collection $students, array $selectedFields, array $customFields, int $tahunAjaran): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'ppdb_export_').'.xlsx';

        // Split selected fields into Sheet 1 (Core) and Sheet 2 (Detail)
        $coreKeys = ['nisn', 'jenis_kelamin', 'sekolah_asal', 'nomor_hp', 'status', 'submitted_at'];

        $sheet1Fields = [];
        $sheet2Fields = [];

        foreach ($selectedFields as $f) {
            if (in_array($f, $coreKeys)) {
                $sheet1Fields[] = $f;
            } else {
                if ($f !== 'nomor_registrasi' && $f !== 'nama_lengkap') {
                    $sheet2Fields[] = $f;
                }
            }
        }

        $hasSheet2 = count($sheet2Fields) > 0;

        // Helper to calculate column widths for a specific sheet's dynamic fields
        $calculateWidths = function ($fields, $students, $customFields, $includeRegistration = true) {
            if ($includeRegistration) {
                $lengths = [
                    1 => 4,  // NO
                    2 => 14, // NO. REGISTRASI
                    3 => 25, // NAMA LENGKAP
                ];
                $startIdx = 4;
            } else {
                $lengths = [
                    1 => 4,  // NO
                    2 => 25, // NAMA LENGKAP
                ];
                $startIdx = 3;
            }

            foreach ($fields as $idx => $f) {
                $colIdx = $idx + $startIdx;
                $label = '';
                switch ($f) {
                    case 'nisn': $label = 'NISN';
                        break;
                    case 'jenis_kelamin': $label = 'L/P';
                        break;
                    case 'sekolah_asal': $label = 'SEKOLAH ASAL';
                        break;
                    case 'nomor_hp': $label = 'NO. HP/WA';
                        break;
                    case 'status': $label = 'STATUS';
                        break;
                    case 'submitted_at': $label = 'TANGGAL DAFTAR';
                        break;
                    case 'tempat_lahir': $label = 'TEMPAT LAHIR';
                        break;
                    case 'tanggal_lahir': $label = 'TANGGAL LAHIR';
                        break;
                    case 'ukuran_baju': $label = 'SERAGAM';
                        break;
                    case 'email': $label = 'EMAIL';
                        break;
                    case 'alamat_lengkap': $label = 'ALAMAT LENGKAP';
                        break;
                    case 'nama_ayah': $label = 'NAMA AYAH';
                        break;
                    case 'nama_ibu': $label = 'NAMA IBU';
                        break;
                    default:
                        $matched = collect($customFields)->firstWhere('id', $f);
                        $label = $matched ? $matched['label'] : $f;
                        if ($f === 'nama_wali') {
                            $label = 'Nama Wali';
                        }
                        break;
                }
                $lengths[$colIdx] = strlen($label);
            }

            foreach ($students as $student) {
                if ($includeRegistration) {
                    $lengths[2] = max($lengths[2], strlen((string) $student->nomor_registrasi));
                    $lengths[3] = max($lengths[3], strlen((string) $student->nama_lengkap));
                } else {
                    $lengths[2] = max($lengths[2], strlen((string) $student->nama_lengkap));
                }

                foreach ($fields as $idx => $f) {
                    $colIdx = $idx + $startIdx;
                    $val = '';
                    switch ($f) {
                        case 'nisn': $val = $student->nisn;
                            break;
                        case 'jenis_kelamin': $val = $student->jenis_kelamin;
                            break;
                        case 'sekolah_asal': $val = $student->sekolah_asal;
                            break;
                        case 'nomor_hp': $val = $student->nomor_hp;
                            break;
                        case 'status': $val = $student->status === 'diterima' ? 'LULUS' : ($student->status === 'ditolak' ? 'TOLAK' : 'PROSES');
                            break;
                        case 'submitted_at': $val = $student->submitted_at?->format('d-m-Y H:i') ?? '';
                            break;
                        case 'tempat_lahir': $val = $student->tempat_lahir;
                            break;
                        case 'tanggal_lahir': $val = $student->tanggal_lahir?->format('d-m-Y') ?? '';
                            break;
                        case 'ukuran_baju': $val = $student->ukuran_baju ?? '-';
                            break;
                        case 'email': $val = $student->email;
                            break;
                        case 'alamat_lengkap': $val = $student->alamat_lengkap;
                            break;
                        case 'nama_ayah': $val = $student->nama_ayah;
                            break;
                        case 'nama_ibu': $val = $student->nama_ibu;
                            break;
                        default:
                            $val = $student->additional_fields[$f] ?? '';
                            if (is_array($val)) {
                                $val = implode(', ', $val);
                            }
                            break;
                    }
                    $lengths[$colIdx] = max($lengths[$colIdx], strlen((string) $val));
                }
            }

            return $lengths;
        };

        $sheet1Lengths = $calculateWidths($sheet1Fields, $students, $customFields, true);
        if ($hasSheet2) {
            $sheet2Lengths = $calculateWidths($sheet2Fields, $students, $customFields, false);
        }

        // Options setup with banners centered on all sheets
        $options = new Options;

        $sheet1TotalCols = count($sheet1Fields) + 3;
        $options->mergeCells(0, 1, $sheet1TotalCols - 1, 1, 0);
        $options->mergeCells(0, 2, $sheet1TotalCols - 1, 2, 0);

        if ($hasSheet2) {
            $sheet2TotalCols = count($sheet2Fields) + 2; // Simplified: NO, NAMA LENGKAP + details
            $options->mergeCells(0, 1, $sheet2TotalCols - 1, 1, 1);
            $options->mergeCells(0, 2, $sheet2TotalCols - 1, 2, 1);
        }

        $sheet3Index = $hasSheet2 ? 2 : 1;
        $options->mergeCells(0, 1, 4, 1, $sheet3Index);
        $options->mergeCells(0, 2, 4, 2, $sheet3Index);

        $writer = new Writer($options);
        $writer->openToFile($tempFile);

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
            ->withFontColor('000000')
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
            ->withBackgroundColor('4F45B2') // MAM Limpung Signature accent
            ->withCellAlignment(CellAlignment::CENTER)
            ->withBorder($thinBorder);

        $dataStyleLeft = (new Style)
            ->withFontSize(10)
            ->withFontName('Arial')
            ->withFontColor('333333')
            ->withBorder($thinBorder)
            ->withCellAlignment(CellAlignment::LEFT);

        $dataStyleCenter = (new Style)
            ->withFontSize(10)
            ->withFontName('Arial')
            ->withFontColor('333333')
            ->withBorder($thinBorder)
            ->withCellAlignment(CellAlignment::CENTER);

        $dataStyleBoldCenter = (new Style)
            ->withFontBold(true)
            ->withFontSize(10)
            ->withFontName('Arial')
            ->withFontColor('333333')
            ->withBorder($thinBorder)
            ->withCellAlignment(CellAlignment::CENTER);

        // --- SHEET 1: DATA UTAMA ---
        $firstSheet = $writer->getCurrentSheet();
        $firstSheet->setName('Data Utama');

        // Apply widths
        foreach ($sheet1Lengths as $colIdx => $maxLength) {
            $width = max(min($maxLength + 4, 45), 10);
            $firstSheet->setColumnWidth($width, $colIdx);
        }

        $writer->addRow(new Row([
            Cell::fromValue('DATA UTAMA CALON SISWA BARU (PPDB) - MA MUHAMMADIYAH LIMPUNG', $titleStyle),
        ]));

        $writer->addRow(new Row([
            Cell::fromValue('Tahun Pelajaran: '.$tahunAjaran.'/'.($tahunAjaran + 1).' | Tanggal Unduh: '.date('d-m-Y H:i:s'), $subTitleStyle),
        ]));

        $writer->addRow(new Row([])); // Spacing

        // Headers
        $h1Cells = [
            Cell::fromValue('NO', $headerStyle),
            Cell::fromValue('NO. REGISTRASI', $headerStyle),
            Cell::fromValue('NAMA LENGKAP', $headerStyle),
        ];
        foreach ($sheet1Fields as $f) {
            switch ($f) {
                case 'nisn': $h1Cells[] = Cell::fromValue('NISN', $headerStyle);
                    break;
                case 'jenis_kelamin': $h1Cells[] = Cell::fromValue('L/P', $headerStyle);
                    break;
                case 'sekolah_asal': $h1Cells[] = Cell::fromValue('SEKOLAH ASAL', $headerStyle);
                    break;
                case 'nomor_hp': $h1Cells[] = Cell::fromValue('NO. HP/WA', $headerStyle);
                    break;
                case 'status': $h1Cells[] = Cell::fromValue('STATUS', $headerStyle);
                    break;
                case 'submitted_at': $h1Cells[] = Cell::fromValue('TANGGAL DAFTAR', $headerStyle);
                    break;
            }
        }
        $writer->addRow(new Row($h1Cells));

        // Data Rows
        foreach ($students as $index => $student) {
            $rowCells = [
                Cell::fromValue($index + 1, $dataStyleCenter),
                Cell::fromValue($student->nomor_registrasi, $dataStyleBoldCenter),
                Cell::fromValue(strtoupper($student->nama_lengkap), $dataStyleLeft),
            ];
            foreach ($sheet1Fields as $f) {
                switch ($f) {
                    case 'nisn': $rowCells[] = Cell::fromValue($student->nisn, $dataStyleCenter);
                        break;
                    case 'jenis_kelamin': $rowCells[] = Cell::fromValue($student->jenis_kelamin, $dataStyleCenter);
                        break;
                    case 'sekolah_asal': $rowCells[] = Cell::fromValue(strtoupper($student->sekolah_asal), $dataStyleLeft);
                        break;
                    case 'nomor_hp': $rowCells[] = Cell::fromValue($student->nomor_hp, $dataStyleCenter);
                        break;
                    case 'status':
                        $statusText = $student->status === 'diterima' ? 'LULUS' : ($student->status === 'ditolak' ? 'TOLAK' : 'PROSES');
                        $rowCells[] = Cell::fromValue($statusText, $dataStyleCenter);
                        break;
                    case 'submitted_at': $rowCells[] = Cell::fromValue($student->submitted_at?->format('d-m-Y H:i') ?? '', $dataStyleCenter);
                        break;
                }
            }
            $writer->addRow(new Row($rowCells));
        }

        // --- SHEET 2: DATA DETAIL (Conditional) ---
        if ($hasSheet2) {
            $sheet2 = $writer->addNewSheetAndMakeItCurrent();
            $sheet2->setName('Data Detail');

            // Apply widths
            foreach ($sheet2Lengths as $colIdx => $maxLength) {
                $width = max(min($maxLength + 4, 45), 10);
                $sheet2->setColumnWidth($width, $colIdx);
            }

            $writer->addRow(new Row([
                Cell::fromValue('DATA DETAIL & LATAR BELAKANG SISWA (PPDB) - MA MUHAMMADIYAH LIMPUNG', $titleStyle),
            ]));

            $writer->addRow(new Row([
                Cell::fromValue('Tahun Pelajaran: '.$tahunAjaran.'/'.($tahunAjaran + 1).' | Tanggal Unduh: '.date('d-m-Y H:i:s'), $subTitleStyle),
            ]));

            $writer->addRow(new Row([])); // Spacing

            // Headers
            $h2Cells = [
                Cell::fromValue('NO', $headerStyle),
                Cell::fromValue('NAMA LENGKAP', $headerStyle),
            ];
            foreach ($sheet2Fields as $f) {
                switch ($f) {
                    case 'tempat_lahir': $h2Cells[] = Cell::fromValue('TEMPAT LAHIR', $headerStyle);
                        break;
                    case 'tanggal_lahir': $h2Cells[] = Cell::fromValue('TANGGAL LAHIR', $headerStyle);
                        break;
                    case 'ukuran_baju': $h2Cells[] = Cell::fromValue('SERAGAM', $headerStyle);
                        break;
                    case 'email': $h2Cells[] = Cell::fromValue('EMAIL', $headerStyle);
                        break;
                    case 'alamat_lengkap': $h2Cells[] = Cell::fromValue('ALAMAT LENGKAP', $headerStyle);
                        break;
                    case 'nama_ayah': $h2Cells[] = Cell::fromValue('NAMA AYAH', $headerStyle);
                        break;
                    case 'nama_ibu': $h2Cells[] = Cell::fromValue('NAMA IBU', $headerStyle);
                        break;
                    default:
                        $matched = collect($customFields)->firstWhere('id', $f);
                        $lbl = $matched ? $matched['label'] : $f;
                        if ($f === 'nama_wali') {
                            $lbl = 'Nama Wali';
                        }
                        $h2Cells[] = Cell::fromValue(strtoupper($lbl), $headerStyle);
                        break;
                }
            }
            $writer->addRow(new Row($h2Cells));

            // Data Rows
            foreach ($students as $index => $student) {
                $rowCells = [
                    Cell::fromValue($index + 1, $dataStyleCenter),
                    Cell::fromValue(strtoupper($student->nama_lengkap), $dataStyleLeft),
                ];
                foreach ($sheet2Fields as $f) {
                    switch ($f) {
                        case 'tempat_lahir': $rowCells[] = Cell::fromValue($student->tempat_lahir, $dataStyleLeft);
                            break;
                        case 'tanggal_lahir': $rowCells[] = Cell::fromValue($student->tanggal_lahir?->format('d-m-Y') ?? '', $dataStyleCenter);
                            break;
                        case 'ukuran_baju': $rowCells[] = Cell::fromValue($student->ukuran_baju ?? '-', $dataStyleCenter);
                            break;
                        case 'email': $rowCells[] = Cell::fromValue($student->email, $dataStyleLeft);
                            break;
                        case 'alamat_lengkap': $rowCells[] = Cell::fromValue($student->alamat_lengkap, $dataStyleLeft);
                            break;
                        case 'nama_ayah': $rowCells[] = Cell::fromValue($student->nama_ayah, $dataStyleLeft);
                            break;
                        case 'nama_ibu': $rowCells[] = Cell::fromValue($student->nama_ibu, $dataStyleLeft);
                            break;
                        default:
                            $val = $student->additional_fields[$f] ?? '';
                            if (is_array($val)) {
                                $val = implode(', ', $val);
                            }
                            $rowCells[] = Cell::fromValue($val, $dataStyleLeft);
                            break;
                    }
                }
                $writer->addRow(new Row($rowCells));
            }
        }

        // --- SHEET 3: RINGKASAN UKURAN BAJU ---
        $sheet3 = $writer->addNewSheetAndMakeItCurrent();
        $sheet3->setName('Ringkasan Ukuran Baju');

        // Column widths
        $sheet3->setColumnWidth(8, 1);
        $sheet3->setColumnWidth(25, 2);
        $sheet3->setColumnWidth(18, 3);
        $sheet3->setColumnWidth(18, 4);
        $sheet3->setColumnWidth(40, 5);

        // Calculations
        $sizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
        $sizeCounts = [];
        foreach ($sizes as $s) {
            $sizeCounts[$s] = 0;
        }
        $sizeCounts['LAINNYA'] = 0;
        $totalWithSizes = 0;

        foreach ($students as $student) {
            $s = strtoupper(trim($student->ukuran_baju ?? ''));
            if (empty($s) || $s === '-') {
                // empty
            } else {
                if (isset($sizeCounts[$s])) {
                    $sizeCounts[$s]++;
                } else {
                    $sizeCounts['LAINNYA']++;
                }
                $totalWithSizes++;
            }
        }

        $totalStudents = count($students);
        $totalMissingSizes = $totalStudents - $totalWithSizes;

        $writer->addRow(new Row([
            Cell::fromValue('LAPORAN RINGKASAN UKURAN SERAGAM OLAHRAGA SISWA BARU', $titleStyle),
        ]));

        $writer->addRow(new Row([
            Cell::fromValue('Tahun Pelajaran: '.$tahunAjaran.'/'.($tahunAjaran + 1).' | Total Calon Siswa: '.$totalStudents, $subTitleStyle),
        ]));

        $writer->addRow(new Row([])); // Spacing

        // KPI card styles
        $kpiLabelStyle = (new Style)
            ->withFontBold(true)
            ->withFontSize(9)
            ->withFontName('Arial')
            ->withFontColor('555555')
            ->withCellAlignment(CellAlignment::CENTER)
            ->withBackgroundColor('EBF5FF')
            ->withBorder($thinBorder);

        $kpiValueStyle = (new Style)
            ->withFontBold(true)
            ->withFontSize(14)
            ->withFontName('Arial')
            ->withFontColor('4F45B2')
            ->withCellAlignment(CellAlignment::CENTER)
            ->withBackgroundColor('EBF5FF')
            ->withBorder($thinBorder);

        $writer->addRow(new Row([
            Cell::fromValue('', (new Style)),
            Cell::fromValue('TOTAL CALON SISWA', $kpiLabelStyle),
            Cell::fromValue('SERAGAM TERDATA', $kpiLabelStyle),
            Cell::fromValue('BELUM ISI UKURAN', $kpiLabelStyle),
        ]));

        $writer->addRow(new Row([
            Cell::fromValue('', (new Style)),
            Cell::fromValue($totalStudents, $kpiValueStyle),
            Cell::fromValue($totalWithSizes, $kpiValueStyle),
            Cell::fromValue($totalMissingSizes, $kpiValueStyle),
        ]));

        $writer->addRow(new Row([])); // Spacing

        // Table Header
        $tableHeaderCells = [
            Cell::fromValue('NO', $headerStyle),
            Cell::fromValue('UKURAN SERAGAM', $headerStyle),
            Cell::fromValue('JUMLAH SISWA', $headerStyle),
            Cell::fromValue('PERSENTASE', $headerStyle),
            Cell::fromValue('VISUALISASI GRAFIK', $headerStyle),
        ];
        $writer->addRow(new Row($tableHeaderCells));

        // Breakdown Rows
        $index = 1;
        $allSizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'LAINNYA', 'BELUM MENGISI'];

        foreach ($allSizes as $size) {
            if ($size === 'BELUM MENGISI') {
                $count = $totalMissingSizes;
            } else {
                $count = $sizeCounts[$size];
            }

            $percentage = $totalStudents > 0 ? ($count / $totalStudents) * 100 : 0;

            // Visual Progress Bar using block chars
            $barLength = 15;
            $filledCount = (int) round(($percentage / 100) * $barLength);
            $progressBar = str_repeat('█', $filledCount).str_repeat('░', $barLength - $filledCount).' '.number_format($percentage, 1).'%';

            $rowCells = [
                Cell::fromValue($index++, $dataStyleCenter),
                Cell::fromValue($size, $dataStyleBoldCenter),
                Cell::fromValue($count, $dataStyleCenter),
                Cell::fromValue(number_format($percentage, 1).'%', $dataStyleCenter),
                Cell::fromValue($progressBar, $dataStyleLeft),
            ];
            $writer->addRow(new Row($rowCells));
        }

        // Final Total Row
        $totalRowStyle = (new Style)
            ->withFontBold(true)
            ->withFontSize(10)
            ->withFontName('Arial')
            ->withFontColor('FFFFFF')
            ->withBackgroundColor('6B7280')
            ->withBorder($thinBorder);

        $totalRowCells = [
            Cell::fromValue('', $totalRowStyle),
            Cell::fromValue('TOTAL KESELURUHAN', $totalRowStyle),
            Cell::fromValue($totalStudents, $totalRowStyle),
            Cell::fromValue('100.0%', $totalRowStyle),
            Cell::fromValue('', $totalRowStyle),
        ];
        $writer->addRow(new Row($totalRowCells));

        $writer->close();

        return $tempFile;
    }
}
