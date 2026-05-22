<!-- 2. Consolidated PPDB Analytics Grid (Small & Clean Boxes) -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
    
    <!-- Card 1: Total Pendaftar -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-3 rounded-none shadow-sm flex flex-col justify-between">
        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Pendaftar</span>
        <div class="flex items-baseline gap-1 mt-1">
            <span class="text-xl font-extrabold text-slate-900 dark:text-white font-mono leading-none">{{ $stats['total'] }}</span>
            <span class="text-[9px] text-slate-400 font-mono">Siswa</span>
        </div>
    </div>

    <!-- Card 2: Menunggu -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-3 rounded-none shadow-sm flex flex-col justify-between border-l-2 border-l-amber-500">
        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Menunggu</span>
        <div class="flex items-baseline gap-1 mt-1">
            <span class="text-xl font-extrabold text-amber-600 dark:text-amber-500 font-mono leading-none">{{ $stats['pending'] }}</span>
            <span class="text-[9px] text-slate-400 font-mono">Siswa</span>
        </div>
    </div>

    <!-- Card 3: Terverifikasi -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-3 rounded-none shadow-sm flex flex-col justify-between border-l-2 border-l-emerald-500">
        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Terverifikasi</span>
        <div class="flex items-baseline gap-1 mt-1">
            <span class="text-xl font-extrabold text-emerald-600 dark:text-emerald-500 font-mono leading-none">{{ $stats['verified'] }}</span>
            <span class="text-[9px] text-slate-400 font-mono">Siswa</span>
        </div>
    </div>

    <!-- Card 4: Tingkat Diterima -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-3 rounded-none shadow-sm flex flex-col justify-between border-l-2 border-l-blue-500">
        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 font-semibold leading-none">Tingkat Diterima</span>
        <div class="mt-1">
            <span class="text-xs font-bold text-slate-800 dark:text-zinc-300 font-mono leading-none">
                {{ $stats['acceptance_rate'] }}%
            </span>
            <div class="w-full bg-slate-100 dark:bg-zinc-800 h-1 mt-1 rounded-none overflow-hidden">
                <div class="bg-blue-500 h-full" style="width: {{ $stats['acceptance_rate'] }}%"></div>
            </div>
        </div>
    </div>

    <!-- Card 5: Jenis Kelamin -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-3 rounded-none shadow-sm flex flex-col justify-between">
        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Gender</span>
        <div class="flex items-center gap-1.5 mt-1">
            <span class="text-[10px] font-mono text-slate-600 dark:text-zinc-400 bg-slate-50 dark:bg-zinc-800/40 px-1 py-0.5 border border-slate-100/60 dark:border-zinc-800 rounded-none">
                L: <strong>{{ $distributions['gender']['L'] }}</strong>
            </span>
            <span class="text-[10px] font-mono text-slate-600 dark:text-zinc-400 bg-slate-50 dark:bg-zinc-800/40 px-1 py-0.5 border border-slate-100/60 dark:border-zinc-800 rounded-none">
                P: <strong>{{ $distributions['gender']['P'] }}</strong>
            </span>
        </div>
    </div>

</div>
