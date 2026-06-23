<!-- Summary Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4">
    <div class="bg-slate-50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 p-4">
        <span class="text-slate-400 dark:text-zinc-500 font-mono text-[9px] uppercase tracking-wider block">Antrean Tertunda</span>
        <span id="stat-pending" class="text-2xl font-bold text-slate-800 dark:text-zinc-100 mt-1 block">{{ $queueStats['total_pending'] }}</span>
    </div>
    <div class="bg-slate-50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 p-4">
        <span class="text-slate-400 dark:text-zinc-500 font-mono text-[9px] uppercase tracking-wider block">Sedang Diproses</span>
        <span id="stat-processing" class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1 block">{{ $queueStats['total_processing'] }}</span>
    </div>
    <div class="bg-slate-50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 p-4">
        <span class="text-slate-400 dark:text-zinc-500 font-mono text-[9px] uppercase tracking-wider block">Total Batch</span>
        <span id="stat-batches" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1 block">{{ $queueStats['total_batches'] }}</span>
    </div>
    <div class="bg-slate-50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 p-4">
        <span class="text-slate-400 dark:text-zinc-500 font-mono text-[9px] uppercase tracking-wider block">Pekerjaan Gagal</span>
        <span id="stat-failed" class="text-2xl font-bold text-rose-600 dark:text-rose-400 mt-1 block">{{ $queueStats['total_failed'] }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 p-4">
    <!-- Pending Jobs by Queue -->
    <div class="border border-slate-200 dark:border-zinc-800">
        <div class="bg-slate-50 dark:bg-zinc-950/40 px-4 py-3 border-b border-slate-200 dark:border-zinc-800">
            <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-700 dark:text-zinc-300">
                Antrean Pekerjaan Tertunda
                <span class="text-slate-400 font-normal normal-case ml-2">(per antrean)</span>
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-950/20 font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
                        <th class="py-3 px-4">Antrean</th>
                        <th class="py-3 px-4 text-right">Jumlah</th>
                        <th class="py-3 px-4 text-right">Percobaan</th>
                        <th class="py-3 px-4">Tertua</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse ($queueJobs as $job)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-900/30 transition-colors">
                            <td class="py-3 px-4">
                                <span class="font-mono text-[10px] bg-slate-100 dark:bg-zinc-800 px-1.5 py-0.5 font-semibold text-slate-700 dark:text-zinc-300">
                                    {{ $job->queue }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-slate-800 dark:text-zinc-200">
                                {{ number_format($job->total) }}
                            </td>
                            <td class="py-3 px-4 text-right font-mono text-slate-500 dark:text-zinc-400">
                                {{ number_format($job->total_attempts) }}
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-500 dark:text-zinc-400">
                                {{ $job->oldest ? date('d/m/Y H:i:s', $job->oldest) : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 dark:text-zinc-600 font-mono">
                                Tidak ada antrean pekerjaan yang tertunda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Job Batches -->
    <div class="border border-slate-200 dark:border-zinc-800">
        <div class="bg-slate-50 dark:bg-zinc-950/40 px-4 py-3 border-b border-slate-200 dark:border-zinc-800">
            <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-700 dark:text-zinc-300">
                Batch Pekerjaan
                <span class="text-slate-400 font-normal normal-case ml-2">(20 terbaru)</span>
            </h3>
        </div>
        <div class="overflow-x-auto max-h-80 overflow-y-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-950/20 font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
                        <th class="py-3 px-4">Nama</th>
                        <th class="py-3 px-4 text-right">Progress</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse ($jobBatches as $batch)
                        @php
                            $progress = $batch->total_jobs > 0
                                ? round(($batch->total_jobs - $batch->pending_jobs) / $batch->total_jobs * 100)
                                : 0;
                            if ($batch->cancelled_at) {
                                $status = 'cancelled';
                                $statusClass = 'bg-rose-100 text-rose-800 dark:bg-rose-950/20 dark:text-rose-400';
                            } elseif ($batch->finished_at) {
                                $status = 'finished';
                                $statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/20 dark:text-emerald-400';
                            } else {
                                $status = 'running';
                                $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-950/20 dark:text-amber-400';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-900/30 transition-colors">
                            <td class="py-3 px-4">
                                <span class="font-semibold text-slate-800 dark:text-zinc-200 block truncate max-w-[120px]" title="{{ $batch->name }}">
                                    {{ $batch->name ?: '(tanpa nama)' }}
                                </span>
                                <span class="text-[9px] font-mono text-slate-400 dark:text-zinc-500 block truncate max-w-[120px]" title="{{ $batch->id }}">ID: {{ substr($batch->id, 0, 12) }}...</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <span class="font-mono font-bold text-slate-700 dark:text-zinc-300">
                                    {{ $batch->total_jobs - $batch->pending_jobs }}/{{ $batch->total_jobs }}
                                </span>
                                <div class="w-full bg-slate-200 dark:bg-zinc-700 h-1.5 mt-1">
                                    <div class="h-1.5 bg-indigo-500 dark:bg-indigo-400 transition-all duration-500" style="width: {{ $progress }}%"></div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 text-[9px] font-mono font-bold uppercase {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                                @if ($batch->failed_jobs > 0)
                                    <div class="text-[9px] text-rose-500 mt-0.5">{{ $batch->failed_jobs }} gagal</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-500 dark:text-zinc-400">
                                {{ $batch->created_at ? date('d/m/Y H:i:s', $batch->created_at) : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 dark:text-zinc-600 font-mono">
                                Belum ada batch pekerjaan yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>