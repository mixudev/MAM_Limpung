@extends('dashboard.layouts.main')

@section('content')
<style>
    /* Premium Responsive Diff Table Styling */
    @media (max-width: 640px) {
        .diff-responsive-table {
            display: block !important;
            width: 100% !important;
        }
        .diff-responsive-table thead {
            display: none !important;
        }
        .diff-responsive-table tbody {
            display: block !important;
            width: 100% !important;
        }
        .diff-responsive-table tr {
            display: block !important;
            width: 100% !important;
            border-bottom: 2px solid #e2e8f0 !important;
            padding: 1rem 0 !important;
            background-color: transparent !important;
        }
        .dark .diff-responsive-table tr {
            border-bottom-color: #27272a !important;
        }
        .diff-responsive-table td {
            display: block !important;
            width: 100% !important;
            padding: 0.5rem 0.75rem !important;
            border: none !important;
            box-sizing: border-box !important;
        }
        .diff-responsive-table td::before {
            content: attr(data-label);
            font-weight: bold;
            display: block;
            text-transform: uppercase;
            font-size: 9px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .dark .diff-responsive-table td::before {
            color: #a1a1aa;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'System Logs';
        }
    });
</script>

<div class="max-w-6xl space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Log Sistem (System Logs)</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Audit trail perubahan data, catatan keamanan, kegagalan antrean (failed jobs), dan log pencadangan sistem.</p>
        </div>
        <div>
            <span class="px-3 py-1 text-[10px] font-mono font-bold bg-indigo-50 dark:bg-indigo-950/20 text-[#4f45b2] dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/30 uppercase tracking-wider">
                Monitoring Panel
            </span>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
        <div class="flex border-b border-slate-200 dark:border-zinc-800 overflow-x-auto">
            @php
                $isSuperAdmin = Auth::user()->hasRole('super-admin');
                $routePrefix = $isSuperAdmin ? 'super-admin' : 'admin';
            @endphp
            <a href="{{ route($routePrefix . '.logs.index', ['tab' => 'activity']) }}" 
               class="px-5 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 whitespace-nowrap {{ $activeTab === 'activity' ? 'border-[#4f45b2] text-[#4f45b2] dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-zinc-200' }}">
                Log Perubahan Data
            </a>
            <a href="{{ route($routePrefix . '.logs.index', ['tab' => 'security']) }}" 
               class="px-5 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 whitespace-nowrap {{ $activeTab === 'security' ? 'border-[#4f45b2] text-[#4f45b2] dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-zinc-200' }}">
                Log Keamanan
            </a>
            <a href="{{ route($routePrefix . '.logs.index', ['tab' => 'failed_jobs']) }}" 
               class="px-5 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 whitespace-nowrap {{ $activeTab === 'failed_jobs' ? 'border-[#4f45b2] text-[#4f45b2] dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-zinc-200' }}">
                Log Failed Jobs
            </a>
            <a href="{{ route($routePrefix . '.logs.index', ['tab' => 'backup']) }}" 
               class="px-5 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 whitespace-nowrap {{ $activeTab === 'backup' ? 'border-[#4f45b2] text-[#4f45b2] dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-zinc-200' }}">
                Log Backup
            </a>
        </div>

        <div class="p-2">
            <!-- 1. Activity Logs Tab -->
            @if ($activeTab === 'activity')
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
                                <th class="py-3 px-4">Waktu</th>
                                <th class="py-3 px-4">Pengguna</th>
                                <th class="py-3 px-4">Aksi / Event</th>
                                <th class="py-3 px-4">Model</th>
                                <th class="py-3 px-4 text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse ($activityLogs as $log)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-900/30 transition-colors">
                                    <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-zinc-400">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-zinc-200">
                                        {{ $log->user ? $log->user->name : 'Sistem / Tamu' }}
                                        <div class="text-[9px] text-slate-400 dark:text-zinc-500 font-mono font-normal mt-0.5">{{ $log->user ? $log->user->email : '' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2 py-0.5 text-[9px] font-mono font-bold rounded-none uppercase mr-2 
                                            {{ $log->event === 'created' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/20 dark:text-emerald-400' : ($log->event === 'deleted' ? 'bg-rose-100 text-rose-800 dark:bg-rose-950/20 dark:text-rose-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-950/20 dark:text-blue-400') }}">
                                            {{ $log->event }}
                                        </span>
                                        <span class="text-slate-600 dark:text-zinc-300 font-medium">{{ $log->description }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-zinc-550 text-[10px]">
                                        {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <button type="button" onclick="showActivityDetails({{ $log->id }})"
                                                class="py-1 px-2.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:hover:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400 font-mono font-bold text-[10px] uppercase tracking-wider transition-colors border border-indigo-200 dark:border-indigo-800/50">
                                            Bandingkan
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 dark:text-zinc-600 font-mono">
                                        Belum ada log perubahan data yang terekam.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($activityLogs->hasPages())
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-zinc-800">
                        {{ $activityLogs->links() }}
                    </div>
                @endif
            @endif

            <!-- 2. Security Logs Tab -->
            @if ($activeTab === 'security')
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
                                <th class="py-3 px-4">Waktu</th>
                                <th class="py-3 px-4">Pengguna</th>
                                <th class="py-3 px-4">Event</th>
                                <th class="py-3 px-4">Keterangan</th>
                                <th class="py-3 px-4">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse ($securityLogs as $log)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-900/30 transition-colors">
                                    <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-zinc-400">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-zinc-200">
                                        {{ $log->user ? $log->user->name : 'Sistem / Tamu' }}
                                        <div class="text-[9px] text-slate-400 dark:text-zinc-500 font-mono font-normal mt-0.5">{{ $log->user ? $log->user->email : '' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2 py-0.5 text-[9px] font-mono font-bold rounded-none uppercase
                                            {{ str_contains($log->event, 'fail') ? 'bg-rose-100 text-rose-800 dark:bg-rose-950/20 dark:text-rose-400' : 'bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-400' }}">
                                            {{ $log->event }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600 dark:text-zinc-300 font-medium">
                                        {{ $log->description }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-zinc-500">
                                        {{ $log->ip_address }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 dark:text-zinc-600 font-mono">
                                        Belum ada log keamanan yang terekam.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($securityLogs->hasPages())
                    <div class="mt-4 p-4 border-t border-slate-100 dark:border-zinc-800">
                        {{ $securityLogs->links() }}
                    </div>
                @endif
            @endif

            <!-- 3. Failed Jobs Tab -->
            @if ($activeTab === 'failed_jobs')
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
                                <th class="py-3 px-4">ID</th>
                                <th class="py-3 px-4">Koneksi / Antrean</th>
                                <th class="py-3 px-4">Pesan Kesalahan</th>
                                <th class="py-3 px-4">Gagal Pada</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse ($failedJobs as $job)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-900/30 transition-colors">
                                    <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-zinc-400">
                                        {{ $job->id }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-zinc-200">
                                        <span class="font-mono text-[10px] bg-slate-100 dark:bg-zinc-800 px-1.5 py-0.5">{{ $job->connection }}</span>
                                        <div class="text-[9px] text-slate-400 dark:text-zinc-500 font-mono font-normal mt-1">Queue: {{ $job->queue }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 max-w-xs truncate text-slate-600 dark:text-zinc-300 font-medium">
                                        {{ Str::limit($job->exception, 80) }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-zinc-400">
                                        {{ $job->failed_at }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="flex justify-end items-center gap-1.5">
                                            <button type="button" onclick="showFailedJobDetails({{ $job->id }})"
                                                    class="py-1 px-2 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:hover:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400 font-mono font-bold text-[9px] uppercase tracking-wider border border-indigo-200 dark:border-indigo-800/50"
                                                    title="Lihat Log Stack Trace Lengkap">
                                                Detail
                                            </button>
                                            
                                            <form action="{{ route($routePrefix . '.logs.failed-job.retry', $job->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit"
                                                        class="py-1 px-2 bg-emerald-600 hover:bg-emerald-700 text-white font-mono font-bold text-[9px] uppercase tracking-wider transition-colors shadow-sm"
                                                        title="Kirim Ulang Pekerjaan">
                                                    Retry
                                                </button>
                                            </form>

                                            <form action="{{ route($routePrefix . '.logs.failed-job.destroy', $job->id) }}" method="POST" class="inline-block" id="forget-form-{{ $job->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="AppPopup.confirm({
                                                            title: 'Lupa Pekerjaan?',
                                                            description: 'Aksi ini akan menghapus log pekerjaan gagal secara permanen.',
                                                            confirmText: 'Ya, Lupakan',
                                                            cancelText: 'Batal',
                                                            onConfirm: () => document.getElementById('forget-form-{{ $job->id }}').submit()
                                                        })"
                                                        class="py-1 px-2 bg-rose-600 hover:bg-rose-700 text-white font-mono font-bold text-[9px] uppercase tracking-wider transition-colors shadow-sm"
                                                        title="Hapus Log Pekerjaan Gagal">
                                                    Forget
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 dark:text-zinc-600 font-mono">
                                        Hebat! Tidak ada pekerjaan antrean yang gagal saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($failedJobs->hasPages())
                    <div class="mt-4 p-4 border-t border-slate-100 dark:border-zinc-800">
                        {{ $failedJobs->links() }}
                    </div>
                @endif
            @endif

            <!-- 4. Backup Logs Tab -->
            @if ($activeTab === 'backup')
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
                                <th class="py-3 px-4">Waktu</th>
                                <th class="py-3 px-4">Nama File</th>
                                <th class="py-3 px-4">Jenis</th>
                                <th class="py-3 px-4">Ukuran</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Drive Upload</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse ($backupLogs as $log)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-900/30 transition-colors">
                                    <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-zinc-400">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-zinc-200 font-mono text-[10px]">
                                        {{ $log->filename }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600 dark:text-zinc-300 font-semibold">
                                        {{ $log->type }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-500 dark:text-zinc-400 font-mono">
                                        {{ $log->formatted_size }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2 py-0.5 text-[9px] font-mono font-bold rounded-none uppercase
                                            {{ $log->status === 'success' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/20 dark:text-emerald-400' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/20 dark:text-rose-400' }}">
                                            {{ $log->status }}
                                        </span>
                                        @if($log->error_message)
                                            <div class="text-[9px] text-rose-500 mt-1 max-w-xs truncate" title="{{ $log->error_message }}">Error: {{ Str::limit($log->error_message, 30) }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 font-mono">
                                        @if ($log->drive_uploaded)
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold text-[10px]">YES</span>
                                        @else
                                            <span class="text-slate-400 dark:text-zinc-650">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <button type="button" onclick="showBackupDetails({{ $log->id }})"
                                                class="py-1 px-2.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:hover:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400 font-mono font-bold text-[10px] uppercase tracking-wider transition-colors border border-indigo-200 dark:border-indigo-800/50">
                                            Detail Log
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400 dark:text-zinc-600 font-mono">
                                        Belum ada log backup yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($backupLogs->hasPages())
                    <div class="mt-4 p-4 border-t border-slate-100 dark:border-zinc-800">
                        {{ $backupLogs->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

@include('dashboard.admin.security.logs.partials.activity')

@include('dashboard.admin.security.logs.partials.failed-job')

@include('dashboard.admin.security.logs.partials.backup')

@include('dashboard.admin.security.logs.partials.script')





@endsection
