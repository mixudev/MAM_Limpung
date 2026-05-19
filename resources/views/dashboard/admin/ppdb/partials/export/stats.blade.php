<!-- Brief Summary Metrics (Geometric Cards) -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
    <!-- Total -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-3.5 rounded-none shadow-sm flex flex-col justify-between">
        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Total Pendaftar</span>
        <div class="flex items-baseline gap-1 mt-1">
            <span class="text-xl font-extrabold text-slate-900 dark:text-white font-mono leading-none">{{ $stats['total'] }}</span>
            <span class="text-[9px] text-slate-400 font-mono">Calon Siswa</span>
        </div>
    </div>

    <!-- Pending -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-3.5 rounded-none shadow-sm flex flex-col justify-between border-l-2 border-l-amber-500">
        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Menunggu Verifikasi</span>
        <div class="flex items-baseline gap-1 mt-1">
            <span class="text-xl font-extrabold text-amber-600 dark:text-amber-500 font-mono leading-none">{{ $stats['pending'] }}</span>
            <span class="text-[9px] text-slate-400 font-mono">Berkas</span>
        </div>
    </div>

    <!-- Verified -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-3.5 rounded-none shadow-sm flex flex-col justify-between border-l-2 border-l-emerald-500">
        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Terverifikasi (Diterima)</span>
        <div class="flex items-baseline gap-1 mt-1">
            <span class="text-xl font-extrabold text-emerald-600 dark:text-emerald-500 font-mono leading-none">{{ $stats['verified'] }}</span>
            <span class="text-[9px] text-slate-400 font-mono">Siswa</span>
        </div>
    </div>

    <!-- Target Kuota -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-3.5 rounded-none shadow-sm flex flex-col justify-between border-l-2 border-l-blue-500">
        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Target Kuota Tampung</span>
        <div class="mt-1">
            <span class="text-xs font-bold text-slate-800 dark:text-zinc-300 font-mono leading-none">
                {{ $stats['verified'] }}/{{ $stats['quota_target'] }} ({{ $stats['quota_percent'] }}%)
            </span>
            <div class="w-full bg-slate-100 dark:bg-zinc-800 h-1 mt-1 rounded-none overflow-hidden">
                <div class="bg-blue-500 h-full" style="width: {{ $stats['quota_percent'] }}%"></div>
            </div>
        </div>
    </div>
</div>
