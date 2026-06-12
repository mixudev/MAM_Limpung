@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bc = document.getElementById('breadcrumb');
        if (bc) bc.textContent = 'Chatbot · Log Aktivitas · Detail';
    });
</script>

@php
    $levelColor = [
        'success' => ['badge' => 'bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-950/30 dark:border-emerald-800 dark:text-emerald-400', 'bar' => 'bg-emerald-500', 'icon' => 'fa-circle-check'],
        'info'    => ['badge' => 'bg-blue-50 border-blue-200 text-blue-700 dark:bg-blue-950/30 dark:border-blue-800 dark:text-blue-400', 'bar' => 'bg-blue-500', 'icon' => 'fa-circle-info'],
        'warning' => ['badge' => 'bg-amber-50 border-amber-200 text-amber-700 dark:bg-amber-950/30 dark:border-amber-800 dark:text-amber-400', 'bar' => 'bg-amber-500', 'icon' => 'fa-triangle-exclamation'],
        'error'   => ['badge' => 'bg-rose-50 border-rose-200 text-rose-700 dark:bg-rose-950/30 dark:border-rose-800 dark:text-rose-400', 'bar' => 'bg-rose-500', 'icon' => 'fa-circle-xmark'],
    ];
    $lc        = $levelColor[$log->level] ?? $levelColor['info'];
    $payload   = $log->payload ?? [];
    $sequence  = $payload['fallback_sequence'] ?? [];
    $hasChain  = count($sequence) > 0;
@endphp

<div class="space-y-6">

    {{-- ── Header ─────────────────────────────────────────────────────────── --}}
    @include('dashboard.admin.chatbot.partials._header', [
        'title'    => 'Detail Log AI',
        'subtitle' => 'Analisis lengkap satu entri log — metadata, rantai percobaan API, dan payload mentah.',
    ])

    {{-- ── Breadcrumb / Back ──────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.chatbot.logs') }}"
           class="inline-flex items-center gap-2 text-xs font-mono font-bold text-slate-500 hover:text-slate-800 dark:hover:text-zinc-200 transition-colors group">
            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            Kembali ke Daftar Log
        </a>
        <span class="text-[10px] font-mono text-slate-400 dark:text-zinc-500">Log ID: #{{ $log->id }}</span>
    </div>

    {{-- ── Metadata Cards ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        {{-- Level --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-4 flex items-start gap-3">
            <div class="w-8 h-8 flex items-center justify-center {{ $lc['bar'] }} text-white shrink-0">
                <i class="fa-solid {{ $lc['icon'] }} text-sm"></i>
            </div>
            <div>
                <p class="text-[10px] font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">Level</p>
                <span class="mt-0.5 inline-block px-2 py-0.5 border text-[9px] font-bold font-mono uppercase tracking-wider {{ $lc['badge'] }}">
                    {{ $log->level }}
                </span>
            </div>
        </div>

        {{-- Waktu --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-4 flex items-start gap-3">
            <div class="w-8 h-8 flex items-center justify-center bg-slate-100 dark:bg-zinc-800 text-slate-500 shrink-0">
                <i class="fa-solid fa-clock text-sm"></i>
            </div>
            <div>
                <p class="text-[10px] font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">Waktu</p>
                <p class="text-xs font-mono font-semibold text-slate-700 dark:text-zinc-200 mt-0.5 leading-snug">
                    {{ $log->created_at->setTimezone('Asia/Jakarta')->format('d M Y') }}<br>
                    <span class="text-slate-400">{{ $log->created_at->setTimezone('Asia/Jakarta')->format('H:i:s') }} WIB</span>
                </p>
            </div>
        </div>

        {{-- Session --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-4 flex items-start gap-3">
            <div class="w-8 h-8 flex items-center justify-center bg-slate-100 dark:bg-zinc-800 text-slate-500 shrink-0">
                <i class="fa-solid fa-comments text-sm"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">Sesi ID</p>
                @if($log->session_id)
                    <p class="text-xs font-mono font-semibold text-indigo-600 dark:text-indigo-400 mt-0.5 truncate" title="{{ $log->session_id }}">
                        {{ substr($log->session_id, 0, 12) }}…
                    </p>
                @else
                    <p class="text-xs font-mono text-slate-400 mt-0.5">—</p>
                @endif
            </div>
        </div>

        {{-- API Key --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-4 flex items-start gap-3">
            <div class="w-8 h-8 flex items-center justify-center bg-slate-100 dark:bg-zinc-800 text-slate-500 shrink-0">
                <i class="fa-solid fa-key text-sm"></i>
            </div>
            <div>
                <p class="text-[10px] font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">API Key</p>
                @if($log->api_key_id)
                    <p class="text-xs font-mono font-semibold text-slate-700 dark:text-zinc-200 mt-0.5">
                        ID: {{ $log->api_key_id }}
                        @if($log->apiKey)
                            <span class="text-[10px] text-slate-400 block">{{ $log->apiKey->provider }} · {{ $log->apiKey->model_name }}</span>
                        @endif
                    </p>
                @else
                    <p class="text-xs font-mono text-slate-400 mt-0.5">—</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Message ─────────────────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800">
        <div class="px-5 py-3 border-b border-slate-100 dark:border-zinc-800 flex items-center gap-2">
            <i class="fa-solid fa-message-lines text-slate-400 text-xs"></i>
            <h2 class="text-xs font-bold font-mono uppercase tracking-wider text-slate-600 dark:text-zinc-400">Pesan Log</h2>
        </div>
        <div class="p-5">
            <p class="text-sm font-mono text-slate-800 dark:text-zinc-100 leading-relaxed break-words">{{ $log->message }}</p>
        </div>
    </div>

    {{-- ── Fallback Chain ──────────────────────────────────────────────────── --}}
    @if($hasChain)
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800">
        <div class="px-5 py-3 border-b border-slate-100 dark:border-zinc-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-diagram-next text-rose-400 text-xs"></i>
                <h2 class="text-xs font-bold font-mono uppercase tracking-wider text-slate-600 dark:text-zinc-400">
                    Rantai Percobaan API Fallback
                </h2>
                <span class="ml-1 px-1.5 py-0.5 bg-rose-100 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400 text-[9px] font-bold font-mono uppercase">
                    {{ count($sequence) }} PERCOBAAN
                </span>
            </div>
            <p class="text-[10px] font-mono text-slate-400">Semua percobaan gagal sebelum respons akhir.</p>
        </div>

        <div class="p-5 space-y-0">
            @foreach($sequence as $i => $attempt)
            @php
                $isLast  = $i === count($sequence) - 1;
                $errType = $attempt['error_type'] ?? 'Unknown Error';
                $errTypeColor = match(true) {
                    str_contains(strtolower($errType), 'limit')        => 'bg-amber-100 dark:bg-amber-950/30 border-amber-200 dark:border-amber-900 text-amber-700 dark:text-amber-400',
                    str_contains(strtolower($errType), 'key')          => 'bg-red-100 dark:bg-red-950/30 border-red-200 dark:border-red-900 text-red-700 dark:text-red-400',
                    str_contains(strtolower($errType), 'model')        => 'bg-purple-100 dark:bg-purple-950/30 border-purple-200 dark:border-purple-900 text-purple-700 dark:text-purple-400',
                    str_contains(strtolower($errType), 'timeout')      => 'bg-orange-100 dark:bg-orange-950/30 border-orange-200 dark:border-orange-900 text-orange-700 dark:text-orange-400',
                    default                                             => 'bg-rose-100 dark:bg-rose-950/30 border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400',
                };
                $provider = strtoupper($attempt['provider'] ?? '?');
            @endphp

            {{-- Timeline step --}}
            <div class="relative flex gap-4">

                {{-- Connector line --}}
                @if(!$isLast)
                <div class="absolute left-[17px] top-10 bottom-0 w-0.5 bg-slate-200 dark:bg-zinc-700"></div>
                @endif

                {{-- Step number bubble --}}
                <div class="shrink-0 w-9 h-9 flex items-center justify-center rounded-full border-2 border-rose-300 dark:border-rose-700 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 font-bold text-xs font-mono z-10 mt-0.5">
                    {{ $i + 1 }}
                </div>

                {{-- Card --}}
                <div class="flex-1 mb-5 bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 p-4 space-y-2.5">

                    {{-- Header row --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2 py-0.5 bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-300 text-[9px] font-bold font-mono uppercase tracking-wider">
                                {{ $provider }}
                            </span>
                            <span class="text-xs font-mono font-semibold text-slate-700 dark:text-zinc-200">
                                API Key ID: {{ $attempt['api_key_id'] ?? '?' }}
                            </span>
                            <span class="text-xs font-mono text-slate-500 dark:text-zinc-400">
                                · Model: <span class="font-semibold text-slate-700 dark:text-zinc-200">{{ $attempt['model_name'] ?? '?' }}</span>
                            </span>
                        </div>
                        <span class="text-[10px] font-mono text-slate-400 shrink-0">
                            @if(!empty($attempt['occurred_at']))
                                {{ \Carbon\Carbon::parse($attempt['occurred_at'])->setTimezone('Asia/Jakarta')->format('H:i:s') }} WIB
                            @endif
                        </span>
                    </div>

                    {{-- Error type badge --}}
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-mono text-slate-400 uppercase tracking-wider">Tipe Error:</span>
                        <span class="px-2 py-0.5 border text-[9px] font-bold font-mono uppercase tracking-wider {{ $errTypeColor }}">
                            {{ $errType }}
                        </span>
                    </div>

                    {{-- Error message --}}
                    <div>
                        <span class="text-[10px] font-mono text-slate-400 uppercase tracking-wider block mb-1">Pesan Error:</span>
                        <p class="text-xs font-mono text-rose-600 dark:text-rose-300 leading-relaxed break-words bg-rose-50/50 dark:bg-rose-950/10 border border-rose-100 dark:border-rose-900/30 px-3 py-2">
                            {{ $attempt['error_message'] ?? '—' }}
                        </p>
                    </div>

                    {{-- Exception trace (if present) --}}
                    @if(!empty($attempt['exception']))
                    <details class="group">
                        <summary class="cursor-pointer text-[10px] font-mono font-bold text-slate-400 hover:text-slate-600 dark:hover:text-zinc-300 uppercase tracking-wider select-none flex items-center gap-1.5">
                            <i class="fa-solid fa-chevron-right text-[8px] group-open:rotate-90 transition-transform"></i>
                            Stack Trace Exception
                        </summary>
                        <pre class="mt-2 p-3 bg-slate-900 dark:bg-black border border-slate-300 dark:border-zinc-700 text-[10px] text-emerald-400 whitespace-pre-wrap overflow-x-auto leading-relaxed">{{ $attempt['exception'] }}</pre>
                    </details>
                    @endif
                </div>
            </div>
            @endforeach

            {{-- Final outcome --}}
            <div class="relative flex gap-4">
                <div class="shrink-0 w-9 h-9 flex items-center justify-center rounded-full border-2 {{ $log->level === 'error' ? 'border-rose-500 bg-rose-100 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400' : 'border-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400' }} mt-0.5">
                    <i class="fa-solid {{ $log->level === 'error' ? 'fa-xmark' : 'fa-check' }} text-xs"></i>
                </div>
                <div class="flex-1 flex items-center">
                    <p class="text-xs font-mono font-semibold {{ $log->level === 'error' ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                        @if($log->level === 'error')
                            ✖ Semua {{ count($sequence) }} percobaan gagal. Tidak ada respons AI yang berhasil dikirim.
                        @else
                            ✔ Respons berhasil dikirim setelah {{ count($sequence) }} percobaan fallback.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Raw Payload ─────────────────────────────────────────────────────── --}}
    @if(!empty($payload))
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800">
        <div class="px-5 py-3 border-b border-slate-100 dark:border-zinc-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-brackets-curly text-slate-400 text-xs"></i>
                <h2 class="text-xs font-bold font-mono uppercase tracking-wider text-slate-600 dark:text-zinc-400">Payload Mentah (JSON)</h2>
            </div>
            <button type="button" id="copyPayloadBtn" onclick="copyPayload()"
                class="inline-flex items-center gap-1.5 py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-400 text-[10px] font-mono font-bold uppercase tracking-wider transition-colors">
                <i class="fa-solid fa-copy text-[9px]"></i> Salin
            </button>
        </div>
        <div class="p-5">
            <pre id="rawPayload" class="p-4 bg-slate-900 dark:bg-black border border-slate-200 dark:border-zinc-800 text-[11px] text-emerald-400 whitespace-pre-wrap overflow-x-auto leading-relaxed max-h-[600px] overflow-y-auto">{{ json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
        </div>
    </div>
    @endif

    {{-- ── Bottom Back ─────────────────────────────────────────────────────── --}}
    <div class="pb-2">
        <a href="{{ route('admin.chatbot.logs') }}"
           class="inline-flex items-center gap-2 text-xs font-mono font-bold text-slate-500 hover:text-slate-800 dark:hover:text-zinc-200 transition-colors group">
            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            Kembali ke Daftar Log
        </a>
    </div>

</div>

<script>
function copyPayload() {
    var pre = document.getElementById('rawPayload');
    var btn = document.getElementById('copyPayloadBtn');
    if (!pre) { return; }
    navigator.clipboard.writeText(pre.textContent).then(function() {
        btn.innerHTML = '<i class="fa-solid fa-check text-[9px]"></i> Tersalin!';
        btn.classList.add('text-emerald-600', 'dark:text-emerald-400');
        setTimeout(function() {
            btn.innerHTML = '<i class="fa-solid fa-copy text-[9px]"></i> Salin';
            btn.classList.remove('text-emerald-600', 'dark:text-emerald-400');
        }, 2000);
    });
}
</script>
@endsection
