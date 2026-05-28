<?php

namespace App\Http\Controllers\Dashboard\Prestasi;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Services\Prestasi\PrestasiExportService;
use App\Services\Prestasi\PrestasiImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminPrestasiIOController extends Controller
{
    /**
     * Export achievements to Excel.
     */
    public function exportExcel(Request $request, PrestasiExportService $exportService): BinaryFileResponse
    {
        Gate::authorize('viewAny', Prestasi::class);

        $query = Prestasi::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('peraih', 'like', "%{$search}%")
                    ->orWhere('penyelenggara', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->input('tingkat'));
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        $prestasis = $query->latest('tanggal_prestasi')->get();
        $tempFile = $exportService->exportExcel($prestasis);

        return response()->download(
            $tempFile,
            'LAPORAN_PRESTASI_MAM_LIMPUNG_'.date('Ymd_His').'.xlsx'
        )->deleteFileAfterSend(true);
    }

    /**
     * Show the dedicated import page.
     */
    public function showImport(): View
    {
        Gate::authorize('create', Prestasi::class);

        return view('dashboard.admin.prestasi.import');
    }

    /**
     * Download Excel Import Template.
     */
    public function downloadTemplate(PrestasiExportService $exportService): BinaryFileResponse
    {
        Gate::authorize('create', Prestasi::class);

        $tempFile = $exportService->generateTemplate();

        return response()->download(
            $tempFile,
            'TEMPLATE_IMPORT_PRESTASI_MAM_LIMPUNG.xlsx'
        )->deleteFileAfterSend(true);
    }

    /**
     * Export achievements to print-friendly PDF.
     */
    public function exportPdf(Request $request)
    {
        Gate::authorize('viewAny', Prestasi::class);

        $query = Prestasi::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('peraih', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->input('tingkat'));
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        $prestasis = $query->latest('tanggal_prestasi')->get();
        $orientation = $request->input('pdf_orientation', 'landscape');

        return view('dashboard.admin.prestasi.export_pdf', [
            'prestasis' => $prestasis,
            'orientation' => $orientation,
            'tingkat' => $request->input('tingkat', 'all'),
            'jenis' => $request->input('jenis', 'all'),
        ]);
    }

    /**
     * Import achievements from Excel.
     */
    public function importExcel(Request $request, PrestasiImportService $importService)
    {
        Gate::authorize('create', Prestasi::class);

        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls', 'max:5120'],
        ], [
            'file_excel.required' => 'Pilih file Excel terlebih dahulu.',
            'file_excel.mimes' => 'Format file harus .xlsx atau .xls.',
            'file_excel.max' => 'Ukuran file maksimal adalah 5MB.',
        ]);

        $file = $request->file('file_excel');

        $result = $importService->importExcel($file->getRealPath(), $request->user()->id);

        if ($result['imported_count'] > 0 && empty($result['errors'])) {
            return redirect()->route('admin.prestasi.index')
                ->with('success', 'Berhasil mengimpor '.$result['imported_count'].' data prestasi secara otomatis.');
        }

        if ($result['imported_count'] > 0 && ! empty($result['errors'])) {
            return redirect()->route('admin.prestasi.import.page')
                ->with('success', 'Berhasil mengimpor '.$result['imported_count'].' data prestasi. '.$result['errors_count'].' baris dilewati karena error.')
                ->with('import_errors', $result['errors']);
        }

        return redirect()->route('admin.prestasi.import.page')
            ->withErrors($result['errors'] ?: ['Gagal memproses file import. Pastikan format kolom sesuai dengan template.']);
    }

    /**
     * Preview Excel data before import.
     */
    public function previewExcel(Request $request, PrestasiImportService $importService)
    {
        Gate::authorize('create', Prestasi::class);

        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls', 'max:5120'],
        ]);

        $file = $request->file('file_excel');
        $result = $importService->previewExcel($file->getRealPath());

        return response()->json($result);
    }

    /**
     * Save/Import data from preview.
     */
    public function saveFromPreview(Request $request)
    {
        Gate::authorize('create', Prestasi::class);

        $request->validate([
            'data' => ['required', 'array'],
        ]);

        $importService = app(PrestasiImportService::class);
        $userId = $request->user()->id;
        $importedCount = 0;
        $failedRows = [];

        $getStringValue = function ($value) {
            if (is_array($value)) {
                if (isset($value['date'])) {
                    return trim((string) $value['date']);
                }

                return '';
            }

            return trim((string) $value);
        };

        foreach ($request->input('data', []) as $row) {
            $rowNumber = $row['row_number'] ?? 0;
            $rowErrors = [];

            $judul = isset($row['judul']) ? $getStringValue($row['judul']) : '';
            $peraih = isset($row['peraih']) ? $getStringValue($row['peraih']) : '';
            $tahunRaw = isset($row['tahun']) ? $getStringValue($row['tahun']) : '';
            $tanggalRaw = isset($row['tanggal']) ? $getStringValue($row['tanggal']) : '';
            $tingkatRaw = isset($row['tingkat']) ? $getStringValue($row['tingkat']) : '';
            $jenisRaw = isset($row['jenis']) ? $getStringValue($row['jenis']) : '';
            $juara = isset($row['juara']) ? $getStringValue($row['juara']) : '';
            $penyelenggara = isset($row['penyelenggara']) ? $getStringValue($row['penyelenggara']) : '';
            $unggulan = isset($row['unggulan']) ? $getStringValue($row['unggulan']) : '';
            $deskripsi = isset($row['deskripsi']) ? $getStringValue($row['deskripsi']) : '';

            if (empty($judul)) {
                $rowErrors[] = 'Judul Prestasi tidak boleh kosong.';
            }
            if (empty($peraih)) {
                $rowErrors[] = 'Peraih tidak boleh kosong.';
            }

            $tahun = (int) $tahunRaw;
            if (empty($tahunRaw) || $tahun < 2000 || $tahun > 2100) {
                $rowErrors[] = 'Tahun harus berupa angka di antara 2000 - 2100.';
            }

            $tanggal = null;
            if (! empty($tanggalRaw)) {
                $tanggal = $importService->parseDate($tanggalRaw);
                if ($tanggal === null) {
                    $rowErrors[] = 'Format tanggal tidak valid.';
                }
            }

            $tingkat = null;
            if (! empty($tingkatRaw)) {
                $tingkat = $importService->normalizeTingkat($tingkatRaw);
                if ($tingkat === null) {
                    $rowErrors[] = 'Tingkat tidak valid (Pilihan: Sekolah, Kabupaten, Provinsi, Nasional, Internasional).';
                }
            } else {
                $rowErrors[] = 'Tingkat tidak boleh kosong.';
            }

            $jenis = null;
            if (! empty($jenisRaw)) {
                $jenis = $importService->normalizeJenis($jenisRaw);
                if ($jenis === null) {
                    $rowErrors[] = 'Jenis tidak valid (Pilihan: Akademik, Non-Akademik).';
                }
            } else {
                $rowErrors[] = 'Jenis tidak boleh kosong.';
            }

            if (! empty($rowErrors)) {
                $failedRows[] = array_merge($row, [
                    'errors' => $rowErrors,
                ]);

                continue;
            }

            try {
                DB::transaction(function () use ($userId, $judul, $peraih, $tahun, $tanggal, $tingkat, $jenis, $juara, $penyelenggara, $unggulan, $deskripsi) {
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
                            'is_featured' => in_array(strtolower($unggulan), ['ya', '1', 'yes', 'true']),
                        ]
                    );
                });
                $importedCount++;
            } catch (\Exception $e) {
                $failedRows[] = array_merge($row, [
                    'errors' => ['Gagal menyimpan: '.$e->getMessage()],
                ]);
            }
        }

        return response()->json([
            'success' => count($failedRows) === 0,
            'imported_count' => $importedCount,
            'failed_rows' => $failedRows,
        ]);
    }
}
