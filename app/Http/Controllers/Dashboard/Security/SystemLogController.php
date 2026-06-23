<?php

namespace App\Http\Controllers\Dashboard\Security;

use App\Http\Controllers\Controller;
use App\Models\BackupLog;
use App\Models\SystemLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SystemLogController extends Controller
{
    /**
     * Display a listing of system logs.
     */
    public function index(Request $request): View
    {
        Gate::authorize('view-users', User::class);

        $activeTab = $request->input('tab', 'activity');

        $activityLogs = null;
        $securityLogs = null;
        $failedJobs = null;
        $backupLogs = null;
        $queueStats = null;
        $queueJobs = null;
        $jobBatches = null;

        if ($activeTab === 'activity') {
            $activityLogs = SystemLog::where('log_type', 'activity')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString();
        } elseif ($activeTab === 'security') {
            $securityLogs = SystemLog::where('log_type', 'security')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString();
        } elseif ($activeTab === 'failed_jobs') {
            $failedJobs = DB::table('failed_jobs')
                ->orderBy('failed_at', 'desc')
                ->paginate(15)
                ->withQueryString();
        } elseif ($activeTab === 'backup') {
            $backupLogs = BackupLog::orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString();
        } elseif ($activeTab === 'job_queue') {
            $queueStats = $this->getQueueStats();
            $queueJobs = DB::table('jobs')
                ->select('queue', DB::raw('count(*) as total'), DB::raw('MIN(created_at) as oldest'), DB::raw('SUM(attempts) as total_attempts'))
                ->groupBy('queue')
                ->orderBy('queue')
                ->get();
            $jobBatches = DB::table('job_batches')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();
        }

        return view('dashboard.admin.security.logs.index', [
            'activeTab' => $activeTab,
            'activityLogs' => $activityLogs,
            'securityLogs' => $securityLogs,
            'failedJobs' => $failedJobs,
            'backupLogs' => $backupLogs,
            'queueStats' => $queueStats,
            'queueJobs' => $queueJobs,
            'jobBatches' => $jobBatches,
        ]);
    }

    /**
     * Display the specified activity details.
     */
    public function showActivity(SystemLog $systemLog): JsonResponse
    {
        Gate::authorize('view-users', User::class);

        $user = $systemLog->user;
        $causer = $user ? "{$user->name} ({$user->email})" : 'Sistem / Tamu';

        // Map differences
        $diff = [];
        $old = $systemLog->old_values ?? [];
        $new = $systemLog->new_values ?? [];
        $keys = array_unique(array_merge(array_keys($old), array_keys($new)));

        foreach ($keys as $key) {
            $oldVal = $old[$key] ?? null;
            $newVal = $new[$key] ?? null;

            // Format boolean or array values for display
            if (is_bool($oldVal)) {
                $oldVal = $oldVal ? 'TRUE' : 'FALSE';
            }
            if (is_bool($newVal)) {
                $newVal = $newVal ? 'TRUE' : 'FALSE';
            }
            if (is_array($oldVal)) {
                $oldVal = json_encode($oldVal, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            }
            if (is_array($newVal)) {
                $newVal = json_encode($newVal, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            }

            $diff[] = [
                'attribute' => $key,
                'old' => $oldVal,
                'new' => $newVal,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'event' => strtoupper($systemLog->event),
                'model' => class_basename($systemLog->model_type),
                'causer' => $causer,
                'description' => $systemLog->description,
                'ip_address' => $systemLog->ip_address,
                'user_agent' => $systemLog->user_agent,
                'timestamp' => $systemLog->created_at->format('d F Y, H:i:s').' WIB',
                'diff' => $diff,
            ],
        ]);
    }

    /**
     * Show failed job exception details.
     */
    public function showFailedJob(int $id): JsonResponse
    {
        Gate::authorize('view-users', User::class);

        $failedJob = DB::table('failed_jobs')->where('id', $id)->first();

        if (! $failedJob) {
            return response()->json([
                'success' => false,
                'message' => 'Log pekerjaan gagal tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $failedJob->id,
                'uuid' => $failedJob->uuid,
                'connection' => $failedJob->connection,
                'queue' => $failedJob->queue,
                'exception' => $failedJob->exception,
                'failed_at' => Carbon::parse($failedJob->failed_at)->format('d F Y, H:i:s').' WIB',
            ],
        ]);
    }

    /**
     * Get queue statistics.
     */
    private function getQueueStats(): array
    {
        return [
            'total_pending' => DB::table('jobs')->count(),
            'total_processing' => DB::table('jobs')->whereNotNull('reserved_at')->count(),
            'total_batches' => DB::table('job_batches')->count(),
            'total_failed' => DB::table('failed_jobs')->count(),
        ];
    }

    /**
     * Get real-time queue data for AJAX polling.
     */
    public function getQueueData(): JsonResponse
    {
        Gate::authorize('view-users', User::class);

        $stats = $this->getQueueStats();

        $queueJobs = DB::table('jobs')
            ->select('queue', DB::raw('count(*) as total'), DB::raw('MIN(created_at) as oldest'), DB::raw('SUM(attempts) as total_attempts'))
            ->groupBy('queue')
            ->orderBy('queue')
            ->get();

        $jobBatches = DB::table('job_batches')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'queueJobs' => $queueJobs,
                'jobBatches' => $jobBatches,
            ],
        ]);
    }

    /**
     * Retry a failed job.
     */
    public function retryFailedJob(int $id): RedirectResponse
    {
        Gate::authorize('view-users', User::class);

        $failedJob = DB::table('failed_jobs')->where('id', $id)->first();

        if ($failedJob) {
            Artisan::call('queue:retry', ['id' => $failedJob->uuid]);

            return redirect()->back()->with(
                'success',
                'Pekerjaan Berhasil Dikirim Ulang!|Pekerjaan dengan UUID '.$failedJob->uuid.' telah dimasukkan kembali ke antrean.'
            );
        }

        return redirect()->back()->with('error', 'Log pekerjaan gagal tidak ditemukan.');
    }

    /**
     * Clean logs by tab and period.
     */
    public function cleanLogs(Request $request): JsonResponse
    {
        Gate::authorize('view-users', User::class);

        $tab = $request->input('tab');
        $period = $request->input('period', 'today');

        $now = now();
        $start = match ($period) {
            'today' => $now->copy()->startOfDay(),
            'week' => $now->copy()->subWeek()->startOfDay(),
            'month' => $now->copy()->subMonth()->startOfDay(),
            'custom' => $request->input('start_date')
                ? Carbon::parse($request->input('start_date'))->startOfDay()
                : null,
            default => null,
        };
        $end = $period === 'custom' && $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : $now;

        if (! $start) {
            return response()->json(['success' => false, 'message' => 'Periode tidak valid.'], 422);
        }

        $deleted = 0;
        $label = '';

        try {
            match ($tab) {
                'activity' => $deleted = SystemLog::where('log_type', 'activity')
                    ->whereBetween('created_at', [$start, $end])->delete(),
                'security' => $deleted = SystemLog::where('log_type', 'security')
                    ->whereBetween('created_at', [$start, $end])->delete(),
                'failed_jobs' => $deleted = DB::table('failed_jobs')
                    ->whereBetween('failed_at', [$start, $end])->delete(),
                'backup' => $deleted = BackupLog::whereBetween('created_at', [$start, $end])->delete(),
                'job_queue' => $deleted = DB::table('jobs')
                    ->whereBetween('created_at', [$start->timestamp, $end->timestamp])->delete(),
                default => throw new \InvalidArgumentException('Tab tidak valid.'),
            };

            $periodLabel = match ($period) {
                'today' => 'Hari Ini',
                'week' => 'Seminggu Terakhir',
                'month' => 'Sebulan Terakhir',
                'custom' => 'Kustom',
                default => $period,
            };

            return response()->json([
                'success' => true,
                'message' => "{$deleted} record {$tab} periode {$periodLabel} berhasil dibersihkan.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan log: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a failed job log entry.
     */
    public function deleteFailedJob(int $id): RedirectResponse
    {
        Gate::authorize('view-users', User::class);

        $failedJob = DB::table('failed_jobs')->where('id', $id)->first();

        if ($failedJob) {
            Artisan::call('queue:forget', ['id' => $failedJob->uuid]);

            return redirect()->back()->with(
                'success',
                ' UUID '.$failedJob->uuid.' berhasil dihapus dari sistem.'
            );
        }

        return redirect()->back()->with('error', 'Log pekerjaan gagal tidak ditemukan.');
    }
}
