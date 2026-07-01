<?php

namespace App\Services\Prestasi;

use App\Models\Prestasi;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\XLSX\Reader;

class PrestasiImportService
{
    public function normalizeTingkat(string $input): ?string
    {
        $clean = strtolower(trim($input));
        $clean = str_replace(['-', '_', ' '], '', $clean);

        return match ($clean) {
            'sekolah' => 'sekolah',
            'kabupatenkota', 'kabupaten/kota', 'kabupaten', 'kota' => 'kabupaten',
            'provinsi', 'prov' => 'provinsi',
            'kwarda' => 'kwarda',
            'nasional', 'nas' => 'nasional',
            'internasional', 'intl', 'int' => 'internasional',
            'umum' => 'umum',
            default => null
        };
    }

    public function parseDate(mixed $dateInput): ?string
    {
        if ($dateInput === null || $dateInput === '') {
            return null;
        }

        try {
            if ($dateInput instanceof \DateTimeInterface) {
                return $dateInput->format('Y-m-d');
            }

            $dateString = trim((string) $dateInput);

            if (empty($dateString)) {
                return null;
            }

            if (is_numeric($dateString)) {
                $timestamp = (int) $dateString;
                if ($timestamp > 30000) {
                    $excelEpoch = new \DateTime('1900-01-01');
                    $excelEpoch->modify('+'.($timestamp - 2).' days');

                    return $excelEpoch->format('Y-m-d');
                }
            }

            $formats = [
                'Y-m-d',
                'Y/m/d',
                'd-m-Y',
                'd/m/Y',
                'm/d/Y',
                'm-d-Y',
                'j/n/Y',
                'j-n-Y',
                'Y-m-d H:i:s',
                'Y/m/d H:i:s',
                'd-m-Y H:i:s',
                'd/m/Y H:i:s',
                'm/d/Y H:i:s',
                'j/n/Y H:i:s',
            ];

            foreach ($formats as $format) {
                $parsed = \DateTime::createFromFormat($format, $dateString);
                if ($parsed && $parsed->format($format) === $dateString) {
                    return $parsed->format('Y-m-d');
                }
            }

            $normalized = $this->normalizeDateString($dateString);

            $time = strtotime($normalized);
            if ($time !== false) {
                return date('Y-m-d', $time);
            }

            return null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeDateString(string $dateString): string
    {
        // Handle date ranges: "21-23 Oktober 2022" -> "21 Oktober 2022"
        $dateString = preg_replace('/^(\d{1,2})\s*[-\/]\s*\d{1,2}\s+/', '$1 ', $dateString);

        // Map Indonesian month names (full & abbreviated) to English
        $monthMap = [
            '/\bjanuari\b/i' => 'January',
            '/\bfebruari\b/i' => 'February',
            '/\bmaret\b/i' => 'March',
            '/\bapril\b/i' => 'April',
            '/\bmei\b/i' => 'May',
            '/\bjuni\b/i' => 'June',
            '/\bjuli\b/i' => 'July',
            '/\bagustus\b/i' => 'August',
            '/\bseptember\b/i' => 'September',
            '/\boktober\b/i' => 'October',
            '/\bnovember\b/i' => 'November',
            '/\bdesember\b/i' => 'December',
            '/\bjan\b/i' => 'January',
            '/\bfeb\b/i' => 'February',
            '/\bmar\b/i' => 'March',
            '/\bapr\b/i' => 'April',
            '/\bjun\b/i' => 'June',
            '/\bjul\b/i' => 'July',
            '/\bag[us]?\b/i' => 'August',
            '/\bsep\b/i' => 'September',
            '/\bokt\b/i' => 'October',
            '/\bnov\b/i' => 'November',
            '/\bdes\b/i' => 'December',
        ];
        $dateString = preg_replace(array_keys($monthMap), array_values($monthMap), $dateString);

        // Replace remaining dashes/slashes between parts with spaces
        $dateString = preg_replace('/\s*[-\/]\s*/', ' ', $dateString);
        $dateString = preg_replace('/\s+/', ' ', trim($dateString));

        // Convert 2-digit years at end: "22" -> "2022", "25" -> "2025"
        $dateString = preg_replace_callback('/\b(\d{2})$/', function ($matches) {
            return '20'.$matches[1];
        }, $dateString);

        return $dateString;
    }

    public function importExcel(string $filePath, int $userId): array
    {
        $reader = new Reader;

        try {
            $reader->open($filePath);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'imported_count' => 0,
                'errors' => ['Gagal membuka berkas Excel: '.$e->getMessage()],
            ];
        }

        $importedCount = 0;
        $errors = [];
        $rowCount = 0;

        $validTingkat = ['sekolah', 'kabupaten', 'kwarda', 'provinsi', 'nasional', 'internasional', 'umum'];

        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->getIndex() !== 0) {
                continue;
            }

            foreach ($sheet->getRowIterator() as $row) {
                $rowCount++;

                if ($rowCount <= 4) {
                    continue;
                }

                $values = [];
                try {
                    foreach ($row as $cell) {
                        $values[] = $cell->getValue();
                    }
                } catch (\Throwable $e1) {
                    try {
                        $reflection = new \ReflectionClass($row);
                        if ($reflection->hasProperty('cells')) {
                            $cellsProperty = $reflection->getProperty('cells');
                            $cellsProperty->setAccessible(true);
                            $cells = $cellsProperty->getValue($row);
                            foreach ($cells as $cell) {
                                $values[] = $cell->getValue();
                            }
                        } else {
                            throw new \Exception('Cannot access cells');
                        }
                    } catch (\Throwable $e2) {
                        $errors[] = 'Baris '.$rowCount.' gagal dibaca: Tidak dapat mengakses sel Excel.';

                        continue;
                    }
                }

                if (empty($values) || (count($values) === 1 && $values[0] === null)) {
                    continue;
                }

                // New column mapping (7 columns)
                // 0: NO, 1: PERAIH, 2: KELAS, 3: JUDUL PRESTASI
                // 4: TANGGAL, 5: TINGKAT, 6: PENYELENGGARA

                $peraih = trim((string) ($values[1] ?? ''));
                $kelas = trim((string) ($values[2] ?? ''));
                $judul = trim((string) ($values[3] ?? ''));
                $tanggalRaw = $values[4] ?? null;
                $tingkatRaw = strtolower(trim((string) ($values[5] ?? '')));
                $penyelenggara = trim((string) ($values[6] ?? ''));

                $rowErrors = [];

                if (empty($judul)) {
                    $rowErrors[] = '[Kolom Judul Prestasi] Tidak boleh kosong.';
                }
                if (empty($peraih)) {
                    $rowErrors[] = '[Kolom Peraih] Tidak boleh kosong.';
                }

                $tanggal = null;
                if (! empty($tanggalRaw)) {
                    $tanggal = $this->parseDate($tanggalRaw);
                    if ($tanggal === null) {
                        $rowErrors[] = '[Kolom Tanggal] Format tidak valid: "'.$tanggalRaw.'". Gunakan format: YYYY-MM-DD, DD/MM/YYYY, atau M/D/YYYY.';
                    }
                }

                $tingkat = null;
                if (! empty($tingkatRaw)) {
                    $tingkat = $this->normalizeTingkat($tingkatRaw);
                }

                if ($tingkat === null && ! empty($tingkatRaw)) {
                    $rowErrors[] = '[Kolom Tingkat] Nilai tidak valid: "'.$tingkatRaw.'". Pilihan: Sekolah, Kabupaten/Kota, Kwarda, Provinsi, Nasional, Internasional, Umum.';
                } elseif ($tingkat === null) {
                    $rowErrors[] = '[Kolom Tingkat] Tidak boleh kosong. Pilihan: Sekolah, Kabupaten/Kota, Kwarda, Provinsi, Nasional, Internasional, Umum.';
                }

                if (! empty($rowErrors)) {
                    $errors[] = 'Baris '.$rowCount.' ('.($judul ?: 'Tanpa Judul').'): '.implode(' ', $rowErrors);

                    continue;
                }

                $tahun = $tanggal ? (int) date('Y', strtotime($tanggal)) : (int) date('Y');

                try {
                    DB::transaction(function () use ($userId, $judul, $peraih, $kelas, $tahun, $tanggal, $tingkat, $penyelenggara) {
                        Prestasi::updateOrCreate(
                            [
                                'judul' => $judul,
                                'peraih' => $peraih,
                                'tahun' => $tahun,
                            ],
                            [
                                'user_id' => $userId,
                                'kelas' => $kelas ?: null,
                                'tanggal_prestasi' => $tanggal,
                                'tingkat' => $tingkat,
                                'penyelenggara' => $penyelenggara ?: null,
                                'jenis' => 'akademik',
                            ]
                        );
                    });
                    $importedCount++;
                } catch (\Exception $dbEx) {
                    $errors[] = 'Baris '.$rowCount.' gagal disimpan ke database: '.$dbEx->getMessage();
                }
            }
            break;
        }

        $reader->close();

        return [
            'success' => count($errors) === 0,
            'imported_count' => $importedCount,
            'errors_count' => count($errors),
            'errors' => $errors,
        ];
    }

    public function previewExcel(string $filePath): array
    {
        $reader = new Reader;

        try {
            $reader->open($filePath);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'total_rows' => 0,
                'preview_data' => [],
                'errors' => ['Gagal membuka berkas Excel: '.$e->getMessage()],
            ];
        }

        $previewData = [];
        $rowCount = 0;
        $errors = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->getIndex() !== 0) {
                continue;
            }

            foreach ($sheet->getRowIterator() as $row) {
                $rowCount++;

                if ($rowCount <= 4) {
                    continue;
                }

                $values = [];
                try {
                    foreach ($row as $cell) {
                        $values[] = $cell->getValue();
                    }
                } catch (\Throwable $e1) {
                    try {
                        $reflection = new \ReflectionClass($row);
                        if ($reflection->hasProperty('cells')) {
                            $cellsProperty = $reflection->getProperty('cells');
                            $cellsProperty->setAccessible(true);
                            $cells = $cellsProperty->getValue($row);
                            foreach ($cells as $cell) {
                                $values[] = $cell->getValue();
                            }
                        }
                    } catch (\Throwable $e2) {
                        $errors[] = 'Baris '.$rowCount.' gagal dibaca.';

                        continue;
                    }
                }

                if (empty($values) || (count($values) === 1 && $values[0] === null)) {
                    continue;
                }

                $tanggalVal = $values[4] ?? '';
                if ($tanggalVal instanceof \DateTimeInterface) {
                    $tanggalVal = $tanggalVal->format('Y-m-d');
                }

                $parsedDate = $this->parseDate($tanggalVal);
                $tahun = $parsedDate ? (int) date('Y', strtotime($parsedDate)) : (int) date('Y');

                $rowData = [
                    'row_number' => $rowCount,
                    'peraih' => trim((string) ($values[1] ?? '')),
                    'kelas' => trim((string) ($values[2] ?? '')),
                    'judul' => trim((string) ($values[3] ?? '')),
                    'tanggal' => $tanggalVal,
                    'tahun' => $tahun,
                    'tingkat' => trim((string) ($values[5] ?? '')),
                    'penyelenggara' => trim((string) ($values[6] ?? '')),
                ];

                $rowData['tingkat_normalized'] = $this->normalizeTingkat($rowData['tingkat']) ?? $rowData['tingkat'];
                $rowData['tanggal_normalized'] = $parsedDate ?? $rowData['tanggal'];

                $previewData[] = $rowData;
            }
            break;
        }

        $reader->close();

        $years = [];
        foreach ($previewData as $row) {
            if (! empty($row['tanggal_normalized'])) {
                $years[] = (int) date('Y', strtotime($row['tanggal_normalized']));
            }
        }
        $years = array_unique($years);

        $existingMap = [];
        if (! empty($years)) {
            $existing = Prestasi::select('judul', 'peraih', 'tahun')
                ->whereIn('tahun', $years)
                ->get();

            foreach ($existing as $item) {
                $key = strtolower(trim($item->judul).'|'.trim($item->peraih).'|'.$item->tahun);
                $existingMap[$key] = true;
            }
        }

        $seen = [];
        foreach ($previewData as &$row) {
            $isDuplicate = false;
            $isFileDuplicate = false;

            if (! empty($row['judul']) && ! empty($row['peraih'])) {
                $tahun = $row['tanggal_normalized'] ? (int) date('Y', strtotime($row['tanggal_normalized'])) : (int) date('Y');
                $fileKey = strtolower($row['judul'].'|'.$row['peraih'].'|'.$tahun);
                if (isset($seen[$fileKey])) {
                    $isFileDuplicate = true;
                } else {
                    $seen[$fileKey] = true;
                }

                if (isset($existingMap[$fileKey])) {
                    $isDuplicate = true;
                }
            }

            $row['is_duplicate'] = $isDuplicate;
            $row['is_file_duplicate'] = $isFileDuplicate;
        }
        unset($row);

        return [
            'success' => count($errors) === 0,
            'total_rows' => count($previewData),
            'preview_data' => $previewData,
            'errors' => $errors,
        ];
    }
}
