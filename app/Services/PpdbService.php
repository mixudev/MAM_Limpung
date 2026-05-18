<?php

namespace App\Services;

use App\Models\PpdbSiswa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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
        $years = PpdbSiswa::selectRaw('YEAR(submitted_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter();

        if ($years->isEmpty()) {
            return collect([(int) date('Y')]);
        }

        return $years->map(fn ($y) => (int) $y);
    }

    /**
     * Compile key metrics counts for a specific registration year.
     *
     * @param int $year
     * @return array{total: int, pending: int, verified: int, rejected: int, quota_target: int, quota_percent: float}
     */
    public function getStats(int $year): array
    {
        $total = PpdbSiswa::whereYear('submitted_at', $year)->count();
        $pending = PpdbSiswa::whereYear('submitted_at', $year)->pending()->count();
        $verified = PpdbSiswa::whereYear('submitted_at', $year)->diterima()->count();
        $rejected = PpdbSiswa::whereYear('submitted_at', $year)->ditolak()->count();

        $quotaPercent = self::TARGET_QUOTA > 0
            ? round(($verified / self::TARGET_QUOTA) * 100, 1)
            : 0;

        return [
            'total'         => $total,
            'pending'       => $pending,
            'verified'      => $verified,
            'rejected'      => $rejected,
            'quota_target'  => self::TARGET_QUOTA,
            'quota_percent' => min($quotaPercent, 100.0),
        ];
    }

    /**
     * Retrieve paginated and filtered applicant records.
     *
     * @param int         $year
     * @param string|null $search
     * @param string|null $status
     * @param int         $perPage
     * @return LengthAwarePaginator
     */
    public function getApplicants(int $year, ?string $search = null, ?string $status = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = PpdbSiswa::whereYear('submitted_at', $year);

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
     * @param int $year
     * @return array{gender: array{L: int, P: int}, sizes: array<string, int>, top_schools: Collection}
     */
    public function getDistributions(int $year): array
    {
        // 1. Gender breakdown
        $genders = PpdbSiswa::whereYear('submitted_at', $year)
            ->select('jenis_kelamin', DB::raw('count(*) as count'))
            ->groupBy('jenis_kelamin')
            ->pluck('count', 'jenis_kelamin')
            ->toArray();

        // 2. Shirt sizes breakdown
        $sizes = PpdbSiswa::whereYear('submitted_at', $year)
            ->select('ukuran_baju', DB::raw('count(*) as count'))
            ->groupBy('ukuran_baju')
            ->pluck('count', 'ukuran_baju')
            ->toArray();

        // 3. Top schools
        $topSchools = PpdbSiswa::whereYear('submitted_at', $year)
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
                'S'    => $sizes['S'] ?? 0,
                'M'    => $sizes['M'] ?? 0,
                'L'    => $sizes['L'] ?? 0,
                'XL'   => $sizes['XL'] ?? 0,
                'XXL'  => $sizes['XXL'] ?? 0,
                'XXXL' => $sizes['XXXL'] ?? 0,
            ],
            'top_schools' => $topSchools,
        ];
    }
}
