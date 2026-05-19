<?php

namespace App\Http\Controllers\Dashboard\Ppdb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Ppdb\StorePpdbApplicantRequest;
use App\Models\PpdbSetting;
use App\Models\PpdbSiswa;
use App\Services\PpdbService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPpdbController extends Controller
{
    public function __construct(protected PpdbService $ppdbService) {}

    /**
     * Display the main PPDB admin panel dashboard.
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
            'years' => $years,
            'selectedYear' => $selectedYear,
            'stats' => $stats,
            'distributions' => $distributions,
            'applicants' => $applicants,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    /**
     * Show candidate details. Returns JSON for dynamic ajax slide-overs.
     */
    public function show(PpdbSiswa $ppdbSiswa): JsonResponse
    {
        $formFields = PpdbSetting::getValue('form_fields', []);
        $requirements = PpdbSetting::getValue('requirements', []);

        $mappedAdditional = [];
        $additional = $ppdbSiswa->additional_fields ?? [];

        // Map dynamic form fields
        foreach ($formFields as $field) {
            $value = $additional[$field['id']] ?? null;
            if ($value !== null && $value !== '') {
                $mappedAdditional[] = [
                    'label' => $field['label'],
                    'value' => $value,
                    'type' => $field['type'],
                ];
            }
        }

        // Map dynamic requirement files (except photo which is handled as foto_siswa)
        $mappedRequirements = [];
        foreach ($requirements as $req) {
            if ($req['id'] === 'foto') {
                continue;
            }
            $value = $additional[$req['id']] ?? null;
            if ($value !== null && $value !== '') {
                $mappedRequirements[] = [
                    'label' => $req['label'],
                    'url' => asset('storage/'.$value),
                    'filename' => basename($value),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => array_merge($ppdbSiswa->toArray(), [
                'foto_url' => $ppdbSiswa->fotoUrl(),
                'status_label' => $ppdbSiswa->statusLabel(),
                'status_color' => $ppdbSiswa->statusColor(),
                'formatted_dob' => $ppdbSiswa->tanggal_lahir?->format('d F Y'),
                'formatted_submission' => $ppdbSiswa->submitted_at?->format('d F Y H:i'),
                'mapped_additional' => $mappedAdditional,
                'mapped_requirements' => $mappedRequirements,
            ]),
        ]);
    }

    /**
     * Show the form to create a new applicant.
     */
    public function create(): View
    {
        $formFields = PpdbSetting::getValue('form_fields', []);
        $requirements = PpdbSetting::getValue('requirements', []);
        $general = PpdbSetting::getValue('general', [
            'tahun_ajaran' => (int) date('Y'),
        ]);

        return view('dashboard.admin.ppdb.create', [
            'formFields' => $formFields,
            'requirements' => $requirements,
            'general' => $general,
        ]);
    }

    /**
     * Store a newly created applicant.
     */
    public function store(StorePpdbApplicantRequest $request)
    {
        $validated = $request->validated();
        $files = $request->allFiles();

        $ppdbSiswa = $this->ppdbService->storeApplicant($validated, $files);

        return redirect()->route('admin.ppdb.index')
            ->with('success', "Pendaftar {$ppdbSiswa->nama_lengkap} berhasil ditambahkan dengan Nomor Registrasi {$ppdbSiswa->nomor_registrasi}.");
    }
}
