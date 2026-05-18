<?php

namespace App\Http\Controllers\Dashboard\Ppdb;

use App\Actions\Ppdb\ExportPpdbExcelAction;
use App\Http\Controllers\Controller;
use App\Models\PpdbSetting;
use App\Models\PpdbSiswa;
use App\Services\PpdbService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPpdbExportController extends Controller
{
    public function __construct(protected PpdbService $ppdbService) {}

    /**
     * Show export configuration page.
     */
    public function exportPage(Request $request): View
    {
        $years = $this->ppdbService->getAvailableYears();
        $selectedYear = (int) $request->input('tahun_ajaran', $years->first() ?? date('Y'));

        $stats = $this->ppdbService->getStats($selectedYear);
        $customFields = PpdbSetting::getValue('form_fields', []);

        return view('dashboard.admin.ppdb.export', [
            'years' => $years,
            'selectedYear' => $selectedYear,
            'stats' => $stats,
            'customFields' => $customFields,
        ]);
    }

    /**
     * Download exported candidates data in Excel (XLSX) or PDF format.
     */
    public function downloadExport(Request $request, ExportPpdbExcelAction $excelAction)
    {
        $request->validate([
            'format' => ['required', 'string', 'in:excel,pdf'],
            'tahun_ajaran' => ['required', 'integer'],
            'status' => ['nullable', 'string', 'in:pending,diterima,ditolak'],
            'fields' => ['required', 'array', 'min:1'],
            'pdf_orientation' => ['nullable', 'string', 'in:portrait,landscape'],
        ], [
            'fields.required' => 'Pilih minimal satu kolom data untuk diexport.',
            'fields.min' => 'Pilih minimal satu kolom data untuk diexport.',
        ]);

        $query = PpdbSiswa::query()->whereYear('submitted_at', $request->input('tahun_ajaran'));
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        $students = $query->orderBy('submitted_at', 'asc')->get();

        $customFields = PpdbSetting::getValue('form_fields', []);

        if ($request->input('format') === 'excel') {
            $tempFile = $excelAction->execute(
                $students,
                $request->input('fields', []),
                $customFields,
                (int) $request->input('tahun_ajaran')
            );

            return response()->download(
                $tempFile,
                'LAPORAN_PPDB_MAM_LIMPUNG_'.$request->input('tahun_ajaran').'_'.date('Ymd_His').'.xlsx'
            )->deleteFileAfterSend(true);
        }

        // Generate Print-to-PDF View
        return view('dashboard.admin.ppdb.export_pdf', [
            'students' => $students,
            'selectedFields' => $request->input('fields', []),
            'customFields' => $customFields,
            'year' => $request->input('tahun_ajaran'),
            'status' => $request->input('status', 'all'),
            'orientation' => $request->input('pdf_orientation', 'portrait'),
        ]);
    }
}
