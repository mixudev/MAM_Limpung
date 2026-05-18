<?php

namespace App\Http\Controllers\Dashboard\Ppdb;

use App\Actions\Ppdb\RejectStudentAction;
use App\Actions\Ppdb\VerifyStudentAction;
use App\Http\Controllers\Controller;
use App\Models\PpdbSiswa;
use App\Services\PpdbService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPpdbController extends Controller
{
    public function __construct(protected PpdbService $ppdbService)
    {
    }

    /**
     * Display the main PPDB admin panel dashboard.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $years = $this->ppdbService->getAvailableYears();
        $selectedYear = (int) $request->input('tahun_ajaran', $years->first() ?? date('Y'));

        $stats = $this->ppdbService->getStats($selectedYear);
        $distributions = $this->ppdbService->getDistributions($selectedYear);

        $search = $request->input('search');
        $status = $request->input('status');
        $applicants = $this->ppdbService->getApplicants($selectedYear, $search, $status);

        return view('dashboard.admin.ppdb.index', [
            'years'         => $years,
            'selectedYear'  => $selectedYear,
            'stats'         => $stats,
            'distributions' => $distributions,
            'applicants'    => $applicants,
            'filters'       => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    /**
     * Show candidate details. Returns JSON for dynamic ajax slide-overs.
     *
     * @param PpdbSiswa $ppdbSiswa
     * @return JsonResponse
     */
    public function show(PpdbSiswa $ppdbSiswa): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => array_merge($ppdbSiswa->toArray(), [
                'foto_url'     => $ppdbSiswa->fotoUrl(),
                'status_label' => $ppdbSiswa->statusLabel(),
                'status_color' => $ppdbSiswa->statusColor(),
                'formatted_dob' => $ppdbSiswa->tanggal_lahir?->format('d F Y'),
                'formatted_submission' => $ppdbSiswa->submitted_at?->format('d F Y H:i'),
            ]),
        ]);
    }

    /**
     * Verify and accept an applicant.
     *
     * @param Request             $request
     * @param PpdbSiswa           $ppdbSiswa
     * @param VerifyStudentAction $action
     * @return RedirectResponse
     */
    public function verify(Request $request, PpdbSiswa $ppdbSiswa, VerifyStudentAction $action): RedirectResponse
    {
        $action->execute($ppdbSiswa, $request->input('catatan_admin'));

        return redirect()->back()->with(
            'success',
            'Verifikasi Berhasil!|Calon siswa ' . $ppdbSiswa->nama_lengkap . ' berhasil diverifikasi.'
        );
    }

    /**
     * Reject an applicant with a custom feedback message.
     *
     * @param Request             $request
     * @param PpdbSiswa           $ppdbSiswa
     * @param RejectStudentAction $action
     * @return RedirectResponse
     */
    public function reject(Request $request, PpdbSiswa $ppdbSiswa, RejectStudentAction $action): RedirectResponse
    {
        $request->validate([
            'catatan_admin' => ['required', 'string', 'min:5'],
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi.',
            'catatan_admin.min'      => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $action->execute($ppdbSiswa, $request->input('catatan_admin'));

        return redirect()->back()->with(
            'success',
            'Penolakan Berhasil!|Pendaftaran calon siswa ' . $ppdbSiswa->nama_lengkap . ' berhasil ditolak.'
        );
    }
}
