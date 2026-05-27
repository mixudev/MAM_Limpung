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

                                            <form action="{{ route($routePrefix . '.logs.failed-job.destroy', $job->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus log pekerjaan gagal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
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

<!-- Modal: Activity Details (Data Changes Comparison) - FULLY RESPONSIVE -->
<div id="activityDetailsModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 max-w-4xl w-full flex flex-col max-h-[85vh] shadow-xl">
        <div class="p-5 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center bg-slate-50 dark:bg-zinc-950/30">
            <h3 class="text-xs font-bold text-slate-800 dark:text-zinc-100 uppercase tracking-wider font-mono">
                Log Perubahan: <span id="act_event" class="text-[#4f45b2] dark:text-indigo-400"></span> - <span id="act_model"></span>
            </h3>
            <button type="button" onclick="closeActivityModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-zinc-200 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1 text-xs space-y-5">
            <!-- Metadata Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-slate-50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 rounded-lg">
                <div class="space-y-1">
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase font-mono text-[9px] tracking-wider">Dilakukan Oleh</span>
                    <span id="act_causer" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase font-mono text-[9px] tracking-wider">Waktu Kejadian</span>
                    <span id="act_time" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
                <div class="col-span-1 md:col-span-2 space-y-1 pt-2 border-t border-slate-200/60 dark:border-zinc-800/80">
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase font-mono text-[9px] tracking-wider">Kredensial Jaringan & Perangkat</span>
                    <span id="act_ip" class="font-mono font-semibold text-[#4f45b2] dark:text-indigo-400 block"></span>
                    <div id="act_ua" class="text-[10px] text-slate-500 dark:text-zinc-400 font-mono mt-1 leading-relaxed bg-white dark:bg-zinc-950 p-2 border border-slate-200 dark:border-zinc-800 overflow-x-auto whitespace-pre-wrap break-all"></div>
                </div>
            </div>

            <!-- Diff Table -->
            <div>
                <h4 class="text-xs font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider mb-2 font-mono">
                    Perbandingan Perubahan Data (Data Diff):
                </h4>
                <div class="border border-slate-200 dark:border-zinc-800 rounded-lg overflow-hidden">
                    <table class="w-full text-left border-collapse diff-responsive-table">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-zinc-950 font-mono font-bold uppercase tracking-wider text-[9px] text-slate-500 dark:text-zinc-400 border-b border-slate-200 dark:border-zinc-800">
                                <th class="py-2.5 px-4 w-1/4">Kolom / Atribut</th>
                                <th class="py-2.5 px-4 w-3/8 bg-rose-500/5 text-rose-700 dark:text-rose-450">Sebelum (Lama)</th>
                                <th class="py-2.5 px-4 w-3/8 bg-emerald-500/5 text-emerald-700 dark:text-emerald-450">Sesudah (Baru)</th>
                            </tr>
                        </thead>
                        <tbody id="diff_body" class="divide-y divide-slate-100 dark:divide-zinc-800 font-mono text-[11px] leading-relaxed">
                            <!-- Diff rows will go here via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950/30 flex justify-end">
            <button type="button" onclick="closeActivityModal()" 
                    class="py-2 px-6 bg-slate-200 hover:bg-slate-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 font-mono font-bold text-xs uppercase tracking-wider transition-all">
                Tutup Perbandingan
            </button>
        </div>
    </div>
</div>

<!-- Modal: Failed Job Exception Details -->
<div id="failedJobModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 max-w-4xl w-full flex flex-col max-h-[85vh] shadow-xl">
        <div class="p-5 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center bg-slate-50 dark:bg-zinc-950/30">
            <h3 class="text-xs font-bold text-slate-800 dark:text-zinc-100 uppercase tracking-wider font-mono">
                Log Detail Pekerjaan Gagal (UUID: <span id="job_uuid" class="text-rose-500"></span>)
            </h3>
            <button type="button" onclick="closeFailedJobModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-zinc-200 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1 text-xs space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-slate-50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 rounded-lg font-mono">
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px] tracking-wider">Koneksi</span>
                    <span id="job_connection" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px] tracking-wider">Queue</span>
                    <span id="job_queue" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px] tracking-wider">Gagal Pada</span>
                    <span id="job_time" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
            </div>

            <div>
                <h4 class="text-xs font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider mb-2 font-mono">Stack Trace / Exception:</h4>
                <pre id="job_exception" class="p-4 bg-rose-50 dark:bg-rose-950/10 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-450 font-mono text-[10px] overflow-x-auto overflow-y-auto max-h-96 leading-relaxed whitespace-pre-wrap select-all rounded-lg"></pre>
            </div>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950/30 flex justify-end gap-2">
            <form id="job_retry_form" method="POST" class="inline-block">
                @csrf
                <button type="submit" 
                        class="py-2 px-6 bg-emerald-600 hover:bg-emerald-700 text-white font-mono font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
                    Retry Job
                </button>
            </form>
            <button type="button" onclick="closeFailedJobModal()" 
                    class="py-2 px-6 bg-slate-200 hover:bg-slate-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 font-mono font-bold text-xs uppercase tracking-wider transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal: Backup Log Details (NEW) -->
<div id="backupDetailsModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 max-w-3xl w-full flex flex-col max-h-[85vh] shadow-xl">
        <div class="p-5 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center bg-slate-50 dark:bg-zinc-950/30">
            <h3 class="text-xs font-bold text-slate-800 dark:text-zinc-100 uppercase tracking-wider font-mono">
                Detail Log Backup Sistem
            </h3>
            <button type="button" onclick="closeBackupModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-zinc-200 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1 text-xs space-y-4">
            <!-- Summary Information -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-slate-50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 rounded-lg font-mono">
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px]">Status</span>
                    <span id="bak_status" class="font-bold text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px]">Jenis</span>
                    <span id="bak_type" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px]">Ukuran File</span>
                    <span id="bak_size" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px]">Waktu Backup</span>
                    <span id="bak_time" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
            </div>

            <!-- Filename Block -->
            <div class="p-3 bg-white dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-lg">
                <span class="text-slate-400 dark:text-zinc-500 font-mono text-[9px] uppercase tracking-wider block mb-1">Nama Berkas (Filename)</span>
                <span id="bak_filename" class="font-mono font-bold text-slate-800 dark:text-zinc-200 text-xs select-all"></span>
            </div>

            <!-- Google Drive Cloud Upload details -->
            <div class="p-3 bg-indigo-50/50 dark:bg-indigo-950/10 border border-indigo-100 dark:border-indigo-900/30 rounded-lg">
                <span class="text-indigo-500 dark:text-indigo-400 font-mono text-[9px] uppercase tracking-wider block mb-2 font-bold">Laporan Unggah Awan (Google Drive Sync):</span>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[9px] font-mono">TUNGGAH BERHASIL?</span>
                        <span id="bak_drive_uploaded" class="font-bold font-mono"></span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[9px] font-mono">ID FILE GOOGLE DRIVE</span>
                        <span id="bak_drive_id" class="font-mono font-bold text-slate-800 dark:text-zinc-200 select-all block truncate"></span>
                    </div>
                    <div class="col-span-1 md:col-span-2 hidden" id="bak_drive_error_area">
                        <span class="text-rose-500 block text-[9px] font-mono font-bold">ERROR UPLOAD DRIVE</span>
                        <span id="bak_drive_error" class="font-mono text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 p-2 block border border-rose-200 dark:border-rose-900/50 select-all"></span>
                    </div>
                </div>
            </div>

            <!-- Error message (Visible only if backup failed) -->
            <div id="bak_error_card" class="p-4 bg-rose-50 dark:bg-rose-950/10 border border-rose-200 dark:border-rose-900/50 rounded-lg hidden">
                <h4 class="text-xs font-bold text-rose-700 dark:text-rose-400 uppercase tracking-wider mb-2 font-mono flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-rose-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Pesan Kesalahan Sistem (System Error Message):
                </h4>
                <p id="bak_error_msg" class="font-mono text-rose-700 dark:text-rose-450 leading-relaxed bg-white dark:bg-zinc-950 p-3 border border-rose-200 dark:border-rose-900/40 select-all whitespace-pre-wrap overflow-x-auto max-h-40"></p>
            </div>

            <!-- Details metadata JSON -->
            <div>
                <h4 class="text-xs font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider mb-2 font-mono">
                    Laporan Rincian Log (Metadata JSON):
                </h4>
                <pre id="bak_details" class="p-4 bg-slate-50 dark:bg-zinc-950/60 border border-slate-200 dark:border-zinc-800 text-slate-600 dark:text-zinc-400 font-mono text-[10px] overflow-x-auto max-h-48 leading-relaxed rounded-lg"></pre>
            </div>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950/30 flex justify-end">
            <button type="button" onclick="closeBackupModal()" 
                    class="py-2 px-6 bg-slate-200 hover:bg-slate-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 font-mono font-bold text-xs uppercase tracking-wider transition-colors">
                Tutup Detail
            </button>
        </div>
    </div>
</div>

<script>
    // Fetch and show activity logs
    function showActivityDetails(logId) {
        const routePrefix = '{{ $routePrefix }}';
        let url = `/${routePrefix}/logs/activity/${logId}`;
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                document.getElementById('act_event').innerText = res.data.event;
                document.getElementById('act_model').innerText = res.data.model;
                document.getElementById('act_causer').innerText = res.data.causer;
                document.getElementById('act_time').innerText = res.data.timestamp;
                document.getElementById('act_ip').innerText = `IP: ${res.data.ip_address}`;
                document.getElementById('act_ua').innerText = `User Agent: ${res.data.user_agent}`;

                let body = document.getElementById('diff_body');
                body.innerHTML = '';

                if (res.data.diff && res.data.diff.length > 0) {
                    res.data.diff.forEach(item => {
                        let row = document.createElement('tr');
                        row.className = 'hover:bg-slate-50 dark:hover:bg-zinc-900/30 transition-colors border-b border-slate-100 dark:border-zinc-800';
                        
                        let oldVal = item.old !== null ? item.old : '<span class="text-slate-400 italic">kosong / NULL</span>';
                        let newVal = item.new !== null ? item.new : '<span class="text-slate-400 italic">kosong / NULL</span>';
                        
                        row.innerHTML = `
                            <td data-label="Kolom / Atribut" class="py-2.5 px-4 font-semibold text-slate-700 dark:text-zinc-400 text-xs">${item.attribute}</td>
                            <td data-label="Sebelum" class="py-2.5 px-4 bg-rose-500/5 text-rose-600 dark:text-rose-400 whitespace-pre-wrap break-all">${oldVal}</td>
                            <td data-label="Sesudah" class="py-2.5 px-4 bg-emerald-500/5 text-emerald-600 dark:text-emerald-400 whitespace-pre-wrap break-all">${newVal}</td>
                        `;
                        body.appendChild(row);
                    });
                } else {
                    body.innerHTML = `
                        <tr>
                            <td colspan="3" class="py-4 text-center text-slate-400 dark:text-zinc-500 italic">Tidak ada detail field yang diubah.</td>
                        </tr>
                    `;
                }

                document.getElementById('activityDetailsModal').classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error('Gagal mengambil detail log:', err);
            alert('Gagal mengambil data log.');
        });
    }

    function closeActivityModal() {
        document.getElementById('activityDetailsModal').classList.add('hidden');
    }

    // Fetch and show failed jobs details
    function showFailedJobDetails(jobId) {
        const routePrefix = '{{ $routePrefix }}';
        let url = `/${routePrefix}/logs/failed-job/${jobId}`;
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                document.getElementById('job_uuid').innerText = res.data.uuid;
                document.getElementById('job_connection').innerText = res.data.connection;
                document.getElementById('job_queue').innerText = res.data.queue;
                document.getElementById('job_time').innerText = res.data.failed_at;
                document.getElementById('job_exception').innerText = res.data.exception;
                
                let retryForm = document.getElementById('job_retry_form');
                retryForm.action = `/${routePrefix}/logs/failed-job/${jobId}/retry`;

                document.getElementById('failedJobModal').classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error('Gagal mengambil detail failed job:', err);
            alert('Gagal mengambil data pekerjaan gagal.');
        });
    }

    function closeFailedJobModal() {
        document.getElementById('failedJobModal').classList.add('hidden');
    }

    // Fetch and show backup log details (NEW)
    function showBackupDetails(logId) {
        const routePrefix = '{{ $routePrefix }}';
        // Note: The backup details endpoint is nested under /backup/log/{id}
        let url = `/admin/backup/log/${logId}`;
        if (routePrefix === 'super-admin') {
            url = `/super-admin/backup/log/${logId}`; // Just in case super-admin accesses it
        }
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                const log = res.log;
                
                // Set Status
                const statusBadge = document.getElementById('bak_status');
                statusBadge.innerText = log.status.toUpperCase();
                if (log.status === 'success') {
                    statusBadge.className = 'px-2.5 py-0.5 text-[10px] font-mono font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/20 dark:text-emerald-400';
                } else {
                    statusBadge.className = 'px-2.5 py-0.5 text-[10px] font-mono font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/20 dark:text-rose-400';
                }

                document.getElementById('bak_type').innerText = log.type;
                document.getElementById('bak_size').innerText = res.formatted_size;
                document.getElementById('bak_time').innerText = res.formatted_date;
                document.getElementById('bak_filename').innerText = log.filename;

                // Drive Upload
                const driveUploaded = document.getElementById('bak_drive_uploaded');
                if (log.drive_uploaded) {
                    driveUploaded.innerText = 'BERHASIL (YES)';
                    driveUploaded.className = 'text-emerald-600 dark:text-emerald-400 font-bold';
                    document.getElementById('bak_drive_id').innerText = log.drive_file_id || '-';
                    document.getElementById('bak_drive_error_area').classList.add('hidden');
                } else {
                    driveUploaded.innerText = 'TIDAK TERUNGGAH (NO)';
                    driveUploaded.className = 'text-slate-500 font-bold';
                    document.getElementById('bak_drive_id').innerText = '-';
                    
                    if (log.drive_error) {
                        document.getElementById('bak_drive_error').innerText = log.drive_error;
                        document.getElementById('bak_drive_error_area').classList.remove('hidden');
                    } else {
                        document.getElementById('bak_drive_error_area').classList.add('hidden');
                    }
                }

                // Error Message Area
                const errorCard = document.getElementById('bak_error_card');
                if (log.error_message) {
                    document.getElementById('bak_error_msg').innerText = log.error_message;
                    errorCard.classList.remove('hidden');
                } else {
                    errorCard.classList.add('hidden');
                }

                // Metadata JSON Details
                document.getElementById('bak_details').innerText = log.details ? JSON.stringify(log.details, null, 4) : '{}';

                document.getElementById('backupDetailsModal').classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error('Gagal mengambil detail backup log:', err);
            alert('Gagal mengambil data log backup.');
        });
    }

    function closeBackupModal() {
        document.getElementById('backupDetailsModal').classList.add('hidden');
    }
</script>
@endsection
