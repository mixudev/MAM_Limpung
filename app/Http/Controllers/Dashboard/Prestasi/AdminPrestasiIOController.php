<?php

namespace App\Http\Controllers\Dashboard\Prestasi;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Services\Prestasi\PrestasiExportService;
use App\Services\Prestasi\PrestasiImportService;
use Illuminate\Http\Request;
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

        $validated = $request->validate([
            'data' => ['required', 'array'],
            'data.*.row_number' => ['required', 'integer'],
            'data.*.tanggal' => ['nullable', 'string'],
            'data.*.tahun' => ['required', 'integer'],
            'data.*.peraih' => ['required', 'string'],
            'data.*.judul' => ['required', 'string'],
            'data.*.juara' => ['nullable', 'string'],
            'data.*.tingkat' => ['required', 'string'],
            'data.*.jenis' => ['required', 'string'],
            'data.*.penyelenggara' => ['nullable', 'string'],
            'data.*.unggulan' => ['nullable', 'string'],
            'data.*.deskripsi' => ['nullable', 'string'],
        ]);

        $importService = app(PrestasiImportService::class);
        $userId = $request->user()->id;
        $importedCount = 0;
        $errors = [];

        foreach ($validated['data'] as $row) {
            try {
                $tanggal = null;
                if (! empty($row['tanggal'])) {
                    $tanggal = $importService->parseDate($row['tanggal']);
                }

                $tingkat = $importService->normalizeTingkat($row['tingkat']);
                $jenis = $importService->normalizeJenis($row['jenis']);

                if (! $tingkat) {
                    $errors[] = 'Baris '.$row['row_number'].': Tingkat tidak valid.';

                    continue;
                }
                if (! $jenis) {
                    $errors[] = 'Baris '.$row['row_number'].': Jenis tidak valid.';

                    continue;
                }

                DB::transaction(function () use ($userId, $row, $tanggal, $tingkat, $jenis) {
                    Prestasi::updateOrCreate(
                        [
                            'judul' => $row['judul'],
                            'peraih' => $row['peraih'],
                            'tahun' => (int) $row['tahun'],
                        ],
                        [
                            'user_id' => $userId,
                            'deskripsi' => $row['deskripsi'] ?: null,
                            'tingkat' => $tingkat,
                            'jenis' => $jenis,
                            'penyelenggara' => $row['penyelenggara'] ?: null,
                            'juara' => $row['juara'] ?: null,
                            'tanggal_prestasi' => $tanggal,
                            'is_featured' => strtolower($row['unggulan']) === 'ya' ? true : false,
                        ]
                    );
                });
                $importedCount++;
            } catch (\Exception $e) {
                $errors[] = 'Baris '.$row['row_number'].': '.$e->getMessage();
            }
        }

        return response()->json([
            'success' => count($errors) === 0,
            'imported_count' => $importedCount,
            'errors' => $errors,
        ]);
    }
}
