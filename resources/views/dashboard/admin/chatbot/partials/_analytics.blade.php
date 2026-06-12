{{-- ═══ ANALYTICS ═══ --}}
<div class="p-6 space-y-6">

    {{-- Metric Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-slate-50 dark:bg-zinc-800/40 border border-slate-200 dark:border-zinc-700 p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-400 dark:text-zinc-500">Total Sesi</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white font-mono mt-1">{{ number_format($totalSessions) }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">percakapan dimulai</p>
                </div>
                <div class="p-2 bg-indigo-50 dark:bg-indigo-950/40 text-[#4f45b2] dark:text-indigo-400">
                    <i class="fa-solid fa-comments text-base"></i>
                </div>
            </div>
        </div>
        <div class="bg-slate-50 dark:bg-zinc-800/40 border border-slate-200 dark:border-zinc-700 p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-400 dark:text-zinc-500">Total Query</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white font-mono mt-1">{{ number_format($totalQueries) }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">permintaan ke AI</p>
                </div>
                <div class="p-2 bg-cyan-50 dark:bg-cyan-950/40 text-cyan-600 dark:text-cyan-400">
                    <i class="fa-solid fa-robot text-base"></i>
                </div>
            </div>
        </div>
        <div class="bg-slate-50 dark:bg-zinc-800/40 border border-slate-200 dark:border-zinc-700 p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-400 dark:text-zinc-500">Rerata Respons</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white font-mono mt-1">{{ number_format($avgResponseTime) }}<span class="text-sm font-normal text-slate-400 ml-1">ms</span></p>
                    <p class="text-[10px] text-slate-400 mt-0.5">waktu rata-rata</p>
                </div>
                <div class="p-2 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400">
                    <i class="fa-solid fa-bolt text-base"></i>
                </div>
            </div>
        </div>
        <div class="bg-slate-50 dark:bg-zinc-800/40 border border-slate-200 dark:border-zinc-700 p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-400 dark:text-zinc-500">Kepuasan</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white font-mono mt-1">{{ $feedbackRatio }}<span class="text-sm font-normal text-slate-400">%</span></p>
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $likes }} suka / {{ $dislikes }} tidak</p>
                </div>
                <div class="p-2 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-thumbs-up text-base"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="border border-slate-200 dark:border-zinc-700 p-5 lg:col-span-2">
            <h3 class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-[#4f45b2]"></i> Trafik 7 Hari Terakhir
            </h3>
            <div class="h-52 relative"><canvas id="trafficChart"></canvas></div>
        </div>
        <div class="border border-slate-200 dark:border-zinc-700 p-5">
            <h3 class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-cyan-500"></i> Distribusi Topik
            </h3>
            <div class="h-52 relative"><canvas id="topicChart"></canvas></div>
        </div>
    </div>

    {{-- Top Questions + Feedback --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="border border-slate-200 dark:border-zinc-700 p-5">
            <h3 class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-fire text-amber-500"></i> Pertanyaan Paling Sering
            </h3>
            <div class="divide-y divide-slate-100 dark:divide-zinc-800">
                @forelse($topQuestions as $i => $q)
                <div class="py-3 flex justify-between items-center">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="text-[10px] font-bold font-mono text-slate-300 dark:text-zinc-600 w-5 shrink-0">{{ $i + 1 }}</span>
                        <span class="text-xs text-slate-700 dark:text-zinc-300 truncate">"{{ $q->query }}"</span>
                    </div>
                    <span class="shrink-0 ml-3 px-2 py-0.5 bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900 text-[#4f45b2] dark:text-indigo-400 text-[10px] font-bold font-mono">{{ $q->count }}×</span>
                </div>
                @empty
                <div class="py-8 text-center text-slate-400 dark:text-zinc-500 text-xs font-mono">Belum ada data pertanyaan.</div>
                @endforelse
            </div>
        </div>
        <div class="border border-slate-200 dark:border-zinc-700 p-5">
            <h3 class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-star text-amber-400"></i> Umpan Balik Pengguna
            </h3>
            @php $total = $likes + $dislikes; $pct = $total > 0 ? round($likes / $total * 100) : 0; @endphp
            <div class="flex items-center justify-around py-6">
                <div class="text-center">
                    <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center mx-auto text-xl text-emerald-600">
                        <i class="fa-solid fa-thumbs-up"></i>
                    </div> 
                    <p class="text-2xl font-bold font-mono text-slate-900 dark:text-white mt-2">{{ $likes }}</p>
                    <p class="text-[9px] font-bold font-mono uppercase tracking-widest text-slate-400 mt-0.5">Membantu</p>
                </div>
                <div class="text-2xl text-slate-200 dark:text-zinc-700 font-bold">/</div>
                <div class="text-center">
                    <div class="w-14 h-14 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 flex items-center justify-center mx-auto text-xl text-red-500">
                        <i class="fa-solid fa-thumbs-down"></i>
                    </div>
                    <p class="text-2xl font-bold font-mono text-slate-900 dark:text-white mt-2">{{ $dislikes }}</p>
                    <p class="text-[9px] font-bold font-mono uppercase tracking-widest text-slate-400 mt-0.5">Kurang</p>
                </div>
            </div>
            @if($total > 0)
            <div class="mt-2">
                <div class="flex justify-between text-[10px] font-mono text-slate-400 mb-1">
                    <span>Tingkat Kepuasan</span>
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $pct }}%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-zinc-800 h-1.5">
                    <div class="bg-emerald-500 h-1.5 transition-all" style="width:{{ $pct }}%"></div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
