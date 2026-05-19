<?php

namespace App\Services;

use App\Jobs\SyncPpdbToGoogleSheetsJob;
use App\Models\PpdbSetting;
use App\Models\PpdbSiswa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PpdbService
{
    /**
     * Target quota of admissions.
     */
    public const TARGET_QUOTA = 120;

    /**
     * Get distinct years of registration to populate the filter dropdown.
     * Defaults to the current year if database is empty.
     *
     * @return Collection<int, int>
     */
    public function getAvailableYears(): Collection
    {
        /** @var array<int, int> $years */
        $years = Cache::remember('ppdb_available_years', 300, function () {
            $years = PpdbSiswa::selectRaw('YEAR(submitted_at) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->filter()
                ->map(fn ($y) => (int) $y)
                ->values()
                ->all(); // Store as plain PHP array — avoids __PHP_Incomplete_Class on deserialization

            return empty($years) ? [(int) date('Y')] : $years;
        });

        return collect($years);
    }

    /**
     * Compile key metrics counts for a specific registration year.
     *
     * @return array{total: int, pending: int, verified: int, rejected: int, quota_target: int, quota_percent: float}
     */
    public function getStats(int $year): array
    {
        $general = PpdbSetting::getValue('general', [
            'target_quota' => self::TARGET_QUOTA,
        ]);
        $quotaTarget = (int) ($general['target_quota'] ?? self::TARGET_QUOTA);

        return Cache::remember("ppdb_stats_{$year}", 300, function () use ($year, $quotaTarget) {
            $start = "{$year}-01-01 00:00:00";
            $end = "{$year}-12-31 23:59:59";

            // Query status counts in a single group-by query
            $counts = PpdbSiswa::whereBetween('submitted_at', [$start, $end])
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $pending = $counts['pending'] ?? 0;
            $verified = $counts['diterima'] ?? 0;
            $rejected = $counts['ditolak'] ?? 0;
            $total = $pending + $verified + $rejected;

            $quotaPercent = $quotaTarget > 0
                ? round(($verified / $quotaTarget) * 100, 1)
                : 0;

            return [
                'total' => $total,
                'pending' => $pending,
                'verified' => $verified,
                'rejected' => $rejected,
                'quota_target' => $quotaTarget,
                'quota_percent' => min($quotaPercent, 100.0),
            ];
        });
    }

    /**
     * Retrieve paginated and filtered applicant records.
     */
    public function getApplicants(int $year, ?string $search = null, ?string $status = null, int $perPage = 10): LengthAwarePaginator
    {
        $start = "{$year}-01-01 00:00:00";
        $end = "{$year}-12-31 23:59:59";

        $query = PpdbSiswa::whereBetween('submitted_at', [$start, $end]);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('sekolah_asal', 'like', "%{$search}%")
                    ->orWhere('nomor_registrasi', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('submitted_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Compute statistics distributions (gender, shirt sizes, and top origin schools).
     *
     * @return array{gender: array{L: int, P: int}, sizes: array<string, int>, top_schools: Collection}
     */
    public function getDistributions(int $year): array
    {
        $start = "{$year}-01-01 00:00:00";
        $end = "{$year}-12-31 23:59:59";

        // 1. Gender breakdown
        $genders = PpdbSiswa::whereBetween('submitted_at', [$start, $end])
            ->select('jenis_kelamin', DB::raw('count(*) as count'))
            ->groupBy('jenis_kelamin')
            ->pluck('count', 'jenis_kelamin')
            ->toArray();

        // 2. Shirt sizes breakdown
        $sizes = PpdbSiswa::whereBetween('submitted_at', [$start, $end])
            ->select('ukuran_baju', DB::raw('count(*) as count'))
            ->groupBy('ukuran_baju')
            ->pluck('count', 'ukuran_baju')
            ->toArray();

        // 3. Top schools
        $topSchools = PpdbSiswa::whereBetween('submitted_at', [$start, $end])
            ->select('sekolah_asal', DB::raw('count(*) as count'))
            ->groupBy('sekolah_asal')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return [
            'gender' => [
                'L' => $genders['L'] ?? 0,
                'P' => $genders['P'] ?? 0,
            ],
            'sizes' => [
                'S' => $sizes['S'] ?? 0,
                'M' => $sizes['M'] ?? 0,
                'L' => $sizes['L'] ?? 0,
                'XL' => $sizes['XL'] ?? 0,
                'XXL' => $sizes['XXL'] ?? 0,
                'XXXL' => $sizes['XXXL'] ?? 0,
            ],
            'top_schools' => $topSchools,
        ];
    }

    /**
     * Store a new student applicant, processing uploads and dynamic fields.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $files
     */
    public function storeApplicant(array $data, array $files): PpdbSiswa
    {
        // 1. Process main student photo upload
        if (isset($files['foto_siswa'])) {
            /** @var UploadedFile $file */
            $file = $files['foto_siswa'];
            $filename = 'ppdb_'.uniqid().'.'.$file->getClientOriginalExtension();
            $data['foto_siswa'] = $file->storeAs('ppdb/photos', $filename, 'public');
        }

        // 2. Package dynamic requirements uploads & custom form fields into additional_fields JSON
        $additional = [];

        // Handle dynamic requirement document uploads
        $requirements = PpdbSetting::getValue('requirements', []);
        foreach ($requirements as $req) {
            if ($req['id'] === 'foto') {
                continue;
            }
            if (isset($files[$req['id']])) {
                /** @var UploadedFile $file */
                $file = $files[$req['id']];
                $filename = 'req_'.$req['id'].'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $path = $file->storeAs('ppdb/requirements', $filename, 'public');
                $additional[$req['id']] = $path;
                unset($data[$req['id']]);
            }
        }

        // Handle custom input fields
        $formFields = PpdbSetting::getValue('form_fields', []);
        foreach ($formFields as $field) {
            if (isset($data[$field['id']])) {
                $additional[$field['id']] = $data[$field['id']];
                unset($data[$field['id']]);
            }
        }

        $data['additional_fields'] = $additional;
        $data['submitted_at'] = now();

        $ppdbSiswa = PpdbSiswa::create($data);

        // Sync to Google Sheets
        SyncPpdbToGoogleSheetsJob::dispatch($ppdbSiswa);

        return $ppdbSiswa;
    }
}
