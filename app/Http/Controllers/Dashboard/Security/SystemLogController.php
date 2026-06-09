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
        }

        return view('dashboard.admin.security.logs.index', [
            'activeTab' => $activeTab,
            'activityLogs' => $activityLogs,
            'securityLogs' => $securityLogs,
            'failedJobs' => $failedJobs,
            'backupLogs' => $backupLogs,
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
