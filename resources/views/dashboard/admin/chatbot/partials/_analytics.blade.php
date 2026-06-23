{{-- ═══ ANALYTICS ═══ --}}
<div class="p-6 space-y-5">

    {{-- ── Row 1: Metric Cards ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-slate-50 dark:bg-zinc-800/40 border border-slate-200 dark:border-zinc-700 p-4 flex items-center gap-4">
            <div class="p-2.5 bg-indigo-50 dark:bg-indigo-950/40 text-[#4f45b2] dark:text-indigo-400 shrink-0">
                <i class="fa-solid fa-comments text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-400 dark:text-zinc-500">Total Sesi</p>
                <p class="text-xl font-bold text-slate-900 dark:text-white font-mono">{{ number_format($totalSessions) }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">percakapan</p>
            </div>
        </div>
        <div class="bg-slate-50 dark:bg-zinc-800/40 border border-slate-200 dark:border-zinc-700 p-4 flex items-center gap-4">
            <div class="p-2.5 bg-cyan-50 dark:bg-cyan-950/40 text-cyan-600 dark:text-cyan-400 shrink-0">
                <i class="fa-solid fa-robot text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-400 dark:text-zinc-500">Total Query</p>
                <p class="text-xl font-bold text-slate-900 dark:text-white font-mono">{{ number_format($totalQueries) }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">permintaan AI</p>
            </div>
        </div>
        <div class="bg-slate-50 dark:bg-zinc-800/40 border border-slate-200 dark:border-zinc-700 p-4 flex items-center gap-4">
            <div class="p-2.5 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 shrink-0">
                <i class="fa-solid fa-bolt text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-400 dark:text-zinc-500">Rerata Respons</p>
                <p class="text-xl font-bold text-slate-900 dark:text-white font-mono">{{ number_format($avgResponseTime) }}<span class="text-sm font-normal text-slate-400 ml-0.5">ms</span></p>
                <p class="text-[10px] text-slate-400 mt-0.5">waktu rata-rata</p>
            </div>
        </div>
        <div class="bg-slate-50 dark:bg-zinc-800/40 border border-slate-200 dark:border-zinc-700 p-4 flex items-center gap-4">
            <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 shrink-0">
                <i class="fa-solid fa-thumbs-up text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-400 dark:text-zinc-500">Kepuasan</p>
                <p class="text-xl font-bold text-slate-900 dark:text-white font-mono">{{ $feedbackRatio }}<span class="text-sm font-normal text-slate-400">%</span></p>
                <p class="text-[10px] text-slate-400 mt-0.5">{{ $likes }}👍 / {{ $dislikes }}👎</p>
            </div>
        </div>
    </div>

    {{-- ── Row 2: Traffic Chart ────────────────────────────────── --}}
    <div class="grid grid-cols-1 gap-3">
        <div class="border border-slate-200 dark:border-zinc-700 p-5">
            <h3 class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-[#4f45b2]"></i> Trafik Chat 7 Hari Terakhir
            </h3>
            <div class="h-48 relative"><canvas id="trafficChart"></canvas></div>
        </div>
    </div>

    {{-- ── Row 3: API Stats (bar) + API Line Chart ──────────────────────── --}}
    @php
        $totalApiCalls = $apiStats->sum('total_calls');
        $providerColors = [
            'gemini'     => ['bar' => 'bg-blue-500',   'badge' => 'bg-blue-50 dark:bg-blue-950/30 border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-400',   'dot' => 'bg-blue-500'],
            'groq'       => ['bar' => 'bg-orange-500', 'badge' => 'bg-orange-50 dark:bg-orange-950/30 border-orange-200 dark:border-orange-800 text-orange-700 dark:text-orange-400', 'dot' => 'bg-orange-500'],
            'deepseek'   => ['bar' => 'bg-cyan-500',   'badge' => 'bg-cyan-50 dark:bg-cyan-950/30 border-cyan-200 dark:border-cyan-800 text-cyan-700 dark:text-cyan-400',   'dot' => 'bg-cyan-500'],
            'openrouter' => ['bar' => 'bg-violet-500', 'badge' => 'bg-violet-50 dark:bg-violet-950/30 border-violet-200 dark:border-violet-800 text-violet-700 dark:text-violet-400', 'dot' => 'bg-violet-500'],
        ];
    @endphp
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">

        {{-- API Usage Bar Stats --}}
        <div class="border border-slate-200 dark:border-zinc-700 p-5 flex flex-col">
            <h3 class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-server text-indigo-500"></i> Penggunaan API
            </h3>

            @if($apiStats->isEmpty())
                <div class="flex-1 flex flex-col items-center justify-center py-6 text-center">
                    <i class="fa-solid fa-circle-nodes text-3xl text-slate-200 dark:text-zinc-700 mb-3"></i>
                    <p class="text-xs font-mono text-slate-400 dark:text-zinc-500">Belum ada data.</p>
                </div>
            @else
                <div class="space-y-3 flex-1">
                    @foreach($apiStats as $stat)
                    @php
                        $key      = $stat->apiKey;
                        $provider = strtolower($key->provider ?? 'unknown');
                        $colors   = $providerColors[$provider] ?? ['bar' => 'bg-slate-400', 'badge' => 'bg-slate-50 border-slate-200 text-slate-600', 'dot' => 'bg-slate-400'];
                        $pct      = $totalApiCalls > 0 ? round($stat->total_calls / $totalApiCalls * 100) : 0;
                        $errors   = $apiErrorStats[$stat->api_key_used_id] ?? 0;
                        $avgMs    = (int) round($stat->avg_ms ?? 0);
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <span class="w-2 h-2 rounded-full shrink-0 {{ $colors['dot'] }}"></span>
                                <span class="text-[10px] font-bold font-mono uppercase tracking-wider text-slate-700 dark:text-zinc-200">{{ strtoupper($provider) }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="px-1.5 py-0.5 {{ $colors['badge'] }} border text-[9px] font-bold font-mono">{{ number_format($stat->total_calls) }}×</span>
                                @if($errors > 0)
                                <span class="px-1.5 py-0.5 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900 text-rose-600 dark:text-rose-400 text-[9px] font-bold font-mono">{{ $errors }}err</span>
                                @endif
                            </div>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                            <div class="{{ $colors['bar'] }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                        <p class="text-[9px] font-mono text-slate-300 dark:text-zinc-600 mt-0.5 truncate" title="{{ $key->model_name ?? '' }}">{{ $key->model_name ?? '?' }} · {{ $avgMs > 0 ? number_format($avgMs).'ms' : '' }} · {{ $pct }}%</p>
                    </div>
                    @endforeach
                </div>

                {{-- Footer --}}
                <div class="pt-3 mt-3 border-t border-slate-100 dark:border-zinc-800 flex items-center justify-between">
                    <span class="text-[10px] font-mono text-slate-400">Total Berhasil</span>
                    <span class="text-sm font-bold font-mono text-slate-800 dark:text-white">{{ number_format($totalApiCalls) }}×</span>
                </div>
            @endif
        </div>

        {{-- API Trend Line Chart --}}
        <div class="border border-slate-200 dark:border-zinc-700 p-5 lg:col-span-2">
            <h3 class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-indigo-400"></i> Tren Harian API per Provider (7 Hari)
            </h3>
            @if($apiStats->isEmpty())
                <div class="flex flex-col items-center justify-center h-48 text-center">
                    <i class="fa-solid fa-circle-nodes text-3xl text-slate-200 dark:text-zinc-700 mb-3"></i>
                    <p class="text-xs font-mono text-slate-400">Belum ada data panggilan API.</p>
                </div>
            @else
                <div class="h-48 relative"><canvas id="apiProviderChart"></canvas></div>
                <div id="apiProviderLegend" class="flex flex-wrap gap-x-5 gap-y-1.5 mt-3"></div>
            @endif
        </div>
    </div>

    {{-- ── Row 4: Top Questions (full width, scrollable) ─────────────── --}}
    <div class="border border-slate-200 dark:border-zinc-700 p-5">
        <h3 class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-fire text-amber-500"></i> Pertanyaan Paling Sering
            <span class="ml-auto text-[9px] font-normal normal-case tracking-normal text-slate-300 dark:text-zinc-600">Top 8 query</span>
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
            @forelse($topQuestions as $i => $q)
            <div class="flex items-center gap-3 px-3 py-2.5 bg-slate-50 dark:bg-zinc-800/40 border border-slate-100 dark:border-zinc-800 min-w-0">
                <span class="text-[10px] font-bold font-mono text-slate-300 dark:text-zinc-600 shrink-0 w-4 text-right">{{ $i + 1 }}</span>
                <span class="text-xs text-slate-700 dark:text-zinc-300 truncate flex-1" title="{{ $q->query }}">"{{ $q->query }}"</span>
                <span class="shrink-0 px-1.5 py-0.5 bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900 text-[#4f45b2] dark:text-indigo-400 text-[9px] font-bold font-mono">{{ $q->count }}×</span>
            </div>
            @empty
            <div class="col-span-4 py-8 text-center text-slate-400 dark:text-zinc-500 text-xs font-mono">Belum ada data pertanyaan.</div>
            @endforelse
        </div>
    </div>

</div>
