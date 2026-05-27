<?php

namespace App\Services\Prestasi;

use App\Models\Prestasi;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\XLSX\Reader;

class PrestasiImportService
{
    /**
     * Normalize and validate Tingkat (case-insensitive, flexible matching).
     *
     * @return string|null Normalized tingkat value or null if invalid
     */
    public function normalizeTingkat(string $input): ?string
    {
        $clean = strtolower(trim($input));
        $clean = str_replace(['-', '_', ' '], '', $clean);

        // Map variations to standard values
        return match ($clean) {
            'sekolah' => 'sekolah',
            'kabupatenkota', 'kabupaten/kota', 'kabupaten', 'kota' => 'kabupaten',
            'provinsi', 'prov' => 'provinsi',
            'nasional', 'nas' => 'nasional',
            'internasional', 'intl', 'int' => 'internasional',
            default => null
        };
    }

    /**
     * Normalize and validate Jenis (case-insensitive, flexible matching).
     *
     * @return string|null Normalized jenis value or null if invalid
     */
    public function normalizeJenis(string $input): ?string
    {
        $clean = strtolower(trim($input));
        $clean = str_replace(['-', '_', ' '], '', $clean);

        return match ($clean) {
            'akademik', 'akademis' => 'akademik',
            'nonakademik', 'non' => 'non_akademik',
            default => null
        };
    }

    /**
     * Parse date from multiple formats flexibly.
     * Supports: YYYY-MM-DD, DD-MM-YYYY, M/D/YYYY, DD/MM/YYYY, YYYY/MM/DD, DateTime objects, and numeric timestamps.
     *
     * @return string|null Date in Y-m-d format or null if parsing fails
     */
    public function parseDate(mixed $dateInput): ?string
    {
        if ($dateInput === null || $dateInput === '') {
            return null;
        }

        try {
            // Handle DateTime objects
            if ($dateInput instanceof \DateTime) {
                return $dateInput->format('Y-m-d');
            }

            // Convert to string
            $dateString = trim((string) $dateInput);

            if (empty($dateString)) {
                return null;
            }

            // Handle numeric timestamps (from Excel)
            if (is_numeric($dateString)) {
                $timestamp = (int) $dateString;
                // Excel timestamps are in days since 1900-01-01
                if ($timestamp > 30000) {
                    // Likely Excel timestamp
                    $excelEpoch = new \DateTime('1900-01-01');
                    $excelEpoch->modify('+'.($timestamp - 2).' days');

                    return $excelEpoch->format('Y-m-d');
                }
            }

            // Try common date formats in order of likelihood
            $formats = [
                'Y-m-d',           // 2026-05-19
                'Y/m/d',           // 2026/05/19
                'd-m-Y',           // 19-05-2026
                'd/m/Y',           // 19/05/2026
                'm/d/Y',           // 05/19/2026
                'm-d-Y',           // 05-19-2026
                'j/n/Y',           // 5/19/2026 (no leading zeros)
                'j-n-Y',           // 5-19-2026 (no leading zeros)
                'Y-m-d H:i:s',     // 2026-05-19 14:30:00
                'Y/m/d H:i:s',     // 2026/05/19 14:30:00
                'd-m-Y H:i:s',     // 19-05-2026 14:30:00
                'd/m/Y H:i:s',     // 19/05/2026 14:30:00
                'm/d/Y H:i:s',     // 05/19/2026 14:30:00
                'j/n/Y H:i:s',     // 5/19/2026 14:30:00
            ];

            foreach ($formats as $format) {
                $parsed = \DateTime::createFromFormat($format, $dateString);
                if ($parsed && $parsed->format($format) === $dateString) {
                    return $parsed->format('Y-m-d');
                }
            }

            // Last resort: use strtotime with lenient parsing
            $time = strtotime($dateString);
            if ($time !== false) {
                return date('Y-m-d', $time);
            }

            return null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Import achievements from an Excel file.
     *
     * @return array{success: bool, imported_count: int, errors: array<string>}
     */
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

        // Valid values for tingkat and jenis
        $validTingkat = ['sekolah', 'kabupaten', 'provinsi', 'nasional', 'internasional'];
        $validJenis = ['akademik', 'non_akademik'];

        foreach ($reader->getSheetIterator() as $sheet) {
            // We only read the first sheet
            if ($sheet->getIndex() !== 0) {
                continue;
            }

            foreach ($sheet->getRowIterator() as $row) {
                $rowCount++;

                // Skip the title block and spacing rows (first 4 rows)
                // Row 1: Title
                // Row 2: Metadata / Date
                // Row 3: Empty spacing row
                // Row 4: Table Headers
                if ($rowCount <= 4) {
                    continue;
                }

                // Extract cell values - OpenSpout v5.7 API
                $values = [];
                try {
                    // OpenSpout v5: Row is iterable directly
                    foreach ($row as $cell) {
                        $values[] = $cell->getValue();
                    }
                } catch (\Throwable $e1) {
                    // Fallback: Try using reflection to access internal cells
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
                            // No cells property found
                            throw new \Exception('Cannot access cells');
                        }
                    } catch (\Throwable $e2) {
                        // Last resort: log error and skip this row
                        $errors[] = 'Baris '.$rowCount.' gagal dibaca: Tidak dapat mengakses sel Excel.';

                        continue;
                    }
                }

                // If the row is empty, skip it
                if (empty($values) || (count($values) === 1 && $values[0] === null)) {
                    continue;
                }

                // Map values to columns (checking offsets to prevent array index errors)
                // Column 0: NO (ignored)
                // Column 1: TANGGAL
                // Column 2: TAHUN
                // Column 3: PERAIH (SISWA/TIM)
                // Column 4: JUDUL PRESTASI
                // Column 5: JUARA
                // Column 6: TINGKAT
                // Column 7: JENIS
                // Column 8: PENYELENGGARA
                // Column 9: UNGGULAN
                // Column 10: DESKRIPSI

                $tanggalRaw = $values[1] ?? null;
                $tahunRaw = $values[2] ?? null;
                $peraih = trim((string) ($values[3] ?? ''));
                $judul = trim((string) ($values[4] ?? ''));
                $juara = trim((string) ($values[5] ?? ''));
                $tingkatRaw = strtolower(trim((string) ($values[6] ?? '')));
                $jenisRaw = strtolower(trim((string) ($values[7] ?? '')));
                $penyelenggara = trim((string) ($values[8] ?? ''));
                $featuredRaw = strtolower(trim((string) ($values[9] ?? '')));
                $deskripsi = trim((string) ($values[10] ?? ''));

                // Validation
                $rowErrors = [];

                if (empty($judul)) {
                    $rowErrors[] = '[Kolom Judul Prestasi] Tidak boleh kosong.';
                }
                if (empty($peraih)) {
                    $rowErrors[] = '[Kolom Peraih Prestasi] Tidak boleh kosong.';
                }

                // Parse & validate Tahun
                $tahun = (int) $tahunRaw;
                if ($tahun < 2000 || $tahun > 2100) {
                    $rowErrors[] = '[Kolom Tahun] Nilai tidak valid: "'.$tahunRaw.'". Harus berada di antara 2000 - 2100.';
                }

                // Parse & validate Tanggal
                $tanggal = null;
                if (! empty($tanggalRaw)) {
                    $tanggal = $this->parseDate($tanggalRaw);
                    if ($tanggal === null) {
                        $rowErrors[] = '[Kolom Tanggal] Format tidak valid: "'.$tanggalRaw.'". Gunakan format: YYYY-MM-DD, DD/MM/YYYY, atau M/D/YYYY.';
                    }
                }

                // Normalize and validate Tingkat (FLEXIBLE - case-insensitive)
                $tingkat = null;
                if (! empty($tingkatRaw)) {
                    $tingkat = $this->normalizeTingkat($tingkatRaw);
                }

                if ($tingkat === null && ! empty($tingkatRaw)) {
                    $rowErrors[] = '[Kolom Tingkat] Nilai tidak valid: "'.$tingkatRaw.'". Pilihan: Sekolah, Kabupaten/Kota, Provinsi, Nasional, Internasional.';
                } elseif ($tingkat === null) {
                    $rowErrors[] = '[Kolom Tingkat] Tidak boleh kosong. Pilihan: Sekolah, Kabupaten/Kota, Provinsi, Nasional, Internasional.';
                }

                // Normalize and validate Jenis (FLEXIBLE - case-insensitive)
                $jenis = null;
                if (! empty($jenisRaw)) {
                    $jenis = $this->normalizeJenis($jenisRaw);
                }

                if ($jenis === null && ! empty($jenisRaw)) {
                    $rowErrors[] = '[Kolom Jenis] Nilai tidak valid: "'.$jenisRaw.'". Pilihan: Akademik, Non-Akademik.';
                } elseif ($jenis === null) {
                    $rowErrors[] = '[Kolom Jenis] Tidak boleh kosong. Pilihan: Akademik, Non-Akademik.';
                }

                // Parse Featured
                $isFeatured = false;
                if (! empty($featuredRaw)) {
                    if ($featuredRaw === 'ya' || $featuredRaw === '1' || $featuredRaw === 'yes' || $featuredRaw === 'true') {
                        $isFeatured = true;
                    }
                }

                // If there are errors on this row, log them and skip saving
                if (! empty($rowErrors)) {
                    $errors[] = 'Baris '.$rowCount.' ('.($judul ?: 'Tanpa Judul').'): '.implode(' ', $rowErrors);

                    continue;
                }

                // Save or update to database
                try {
                    DB::transaction(function () use ($userId, $judul, $deskripsi, $tingkat, $jenis, $penyelenggara, $peraih, $juara, $tahun, $tanggal, $isFeatured) {
                        Prestasi::updateOrCreate(
                            [
                                'judul' => $judul,
                                'peraih' => $peraih,
                                'tahun' => $tahun,
                            ],
                            [
                                'user_id' => $userId,
                                'deskripsi' => $deskripsi ?: null,
                                'tingkat' => $tingkat,
                                'jenis' => $jenis,
                                'penyelenggara' => $penyelenggara ?: null,
                                'juara' => $juara ?: null,
                                'tanggal_prestasi' => $tanggal,
                                'is_featured' => $isFeatured,
                            ]
                        );
                    });
                    $importedCount++;
                } catch (\Exception $dbEx) {
                    $errors[] = 'Baris '.$rowCount.' gagal disimpan ke database: '.$dbEx->getMessage();
                }
            }
            break; // Read first sheet only
        }

        $reader->close();

        return [
            'success' => count($errors) === 0,
            'imported_count' => $importedCount,
            'errors_count' => count($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Preview Excel data without strict validation.
     * Returns processed data for user to review and edit before import.
     *
     * @return array{success: bool, total_rows: int, preview_data: array, errors: array<string>}
     */
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
            // Only read first sheet
            if ($sheet->getIndex() !== 0) {
                continue;
            }

            foreach ($sheet->getRowIterator() as $row) {
                $rowCount++;

                // Skip title rows (1-4)
                if ($rowCount <= 4) {
                    continue;
                }

                // Extract cell values
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

                // Skip empty rows
                if (empty($values) || (count($values) === 1 && $values[0] === null)) {
                    continue;
                }

                // Extract values (lenient - don't validate yet)
                $rowData = [
                    'row_number' => $rowCount,
                    'tanggal' => $values[1] ?? '',
                    'tahun' => $values[2] ?? '',
                    'peraih' => trim((string) ($values[3] ?? '')),
                    'judul' => trim((string) ($values[4] ?? '')),
                    'juara' => trim((string) ($values[5] ?? '')),
                    'tingkat' => trim((string) ($values[6] ?? '')),
                    'jenis' => trim((string) ($values[7] ?? '')),
                    'penyelenggara' => trim((string) ($values[8] ?? '')),
                    'unggulan' => trim((string) ($values[9] ?? '')),
                    'deskripsi' => trim((string) ($values[10] ?? '')),
                ];

                // Try to normalize some fields for preview
                $rowData['tingkat_normalized'] = $this->normalizeTingkat($rowData['tingkat']) ?? $rowData['tingkat'];
                $rowData['jenis_normalized'] = $this->normalizeJenis($rowData['jenis']) ?? $rowData['jenis'];
                $rowData['tanggal_normalized'] = $this->parseDate($rowData['tanggal']) ?? $rowData['tanggal'];

                $previewData[] = $rowData;
            }
            break;
        }

        $reader->close();

        return [
            'success' => count($errors) === 0,
            'total_rows' => count($previewData),
            'preview_data' => $previewData,
            'errors' => $errors,
        ];
    }
}
