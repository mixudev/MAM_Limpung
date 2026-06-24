<?php

namespace App\Services;

use App\Jobs\SyncPpdbToGoogleSheetsJob;
use App\Models\AcademicYear;
use App\Models\PpdbSetting;
use App\Models\PpdbSiswa;
use App\Models\RegistrationWave;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PpdbService
{
    public function getAvailableYears(): Collection
    {
        $years = Cache::remember('ppdb_available_years', 300, function () {
            $years = AcademicYear::orderBy('year', 'desc')
                ->pluck('year')
                ->toArray();

            return empty($years) ? [(int) date('Y')] : $years;
        });

        return collect($years);
    }

    public function getStats(int $year): array
    {
        return Cache::remember("ppdb_stats_{$year}", 300, function () use ($year) {
            $start = "{$year}-01-01 00:00:00";
            $end = "{$year}-12-31 23:59:59";

            $counts = PpdbSiswa::whereBetween('submitted_at', [$start, $end])
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $pending = $counts['pending'] ?? 0;
            $verified = $counts['diterima'] ?? 0;
            $rejected = $counts['ditolak'] ?? 0;
            $total = $pending + $verified + $rejected;

            $acceptanceRate = $total > 0
                ? round(($verified / $total) * 100, 1)
                : 0.0;

            return [
                'total' => $total,
                'pending' => $pending,
                'verified' => $verified,
                'rejected' => $rejected,
                'acceptance_rate' => min($acceptanceRate, 100.0),
            ];
        });
    }

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

    public function getDistributions(int $year): array
    {
        $start = "{$year}-01-01 00:00:00";
        $end = "{$year}-12-31 23:59:59";

        $genders = PpdbSiswa::whereBetween('submitted_at', [$start, $end])
            ->select('jenis_kelamin', DB::raw('count(*) as count'))
            ->groupBy('jenis_kelamin')
            ->pluck('count', 'jenis_kelamin')
            ->toArray();

        $sizes = PpdbSiswa::whereBetween('submitted_at', [$start, $end])
            ->select('ukuran_baju', DB::raw('count(*) as count'))
            ->groupBy('ukuran_baju')
            ->pluck('count', 'ukuran_baju')
            ->toArray();

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

    public function storeApplicant(array $data, array $files): PpdbSiswa
    {
        if (isset($files['foto_siswa'])) {
            $file = $files['foto_siswa'];
            $filename = 'ppdb_'.uniqid().'.'.$file->getClientOriginalExtension();
            $data['foto_siswa'] = $file->storeAs('ppdb/photos', $filename, 'public');
        }

        $data['registration_wave_id'] = $this->detectActiveWaveId();

        $additional = [];

        $requirements = PpdbSetting::getValue('requirements', []);
        foreach ($requirements as $req) {
            if ($req['id'] === 'foto') {
                continue;
            }
            if (isset($files[$req['id']])) {
                $file = $files[$req['id']];
                $filename = 'req_'.$req['id'].'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $path = $file->storeAs('ppdb/requirements', $filename, 'public');
                $additional[$req['id']] = $path;
                unset($data[$req['id']]);
            }
        }

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

        SyncPpdbToGoogleSheetsJob::dispatch($ppdbSiswa);

        return $ppdbSiswa;
    }

    public function detectActiveWaveId(): ?int
    {
        $today = now()->format('Y-m-d');

        $wave = RegistrationWave::where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereDate('end_date', '>=', $today)
                    ->orWhereNull('end_date');
            })
            ->orderBy('start_date')
            ->first();

        return $wave?->id;
    }

    public function isOpen(): bool
    {
        return Cache::remember('ppdb_is_open', 300, function () {
            $general = PpdbSetting::getValue('general', ['is_open' => true]);

            if (! ($general['is_open'] ?? true)) {
                return false;
            }

            $hasActiveWave = RegistrationWave::where('is_active', true)
                ->whereDate('start_date', '<=', now()->format('Y-m-d'))
                ->where(function ($q) {
                    $q->whereDate('end_date', '>=', now()->format('Y-m-d'))
                        ->orWhereNull('end_date');
                })
                ->exists();

            return $hasActiveWave;
        });
    }

    public function getActiveWaves(): Collection
    {
        return RegistrationWave::whereHas('academicYear', fn ($q) => $q->where('is_active', true))
            ->where(fn ($q) => $q->where('type', 'wave')->where('is_active', true)->orWhere('type', 'date'))
            ->orderBy('start_date')
            ->get();
    }
}
