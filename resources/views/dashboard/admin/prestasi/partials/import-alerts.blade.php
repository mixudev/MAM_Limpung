{{-- ── Flash Messages ───────────────────────────────────────────────────── --}}
@if (session('success'))
    <div id="flash-ok"
        class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-300 dark:border-emerald-700/50 p-4 flex items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-emerald-800 dark:text-emerald-400 text-sm font-semibold">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
        <button onclick="this.parentElement.remove()"
            class="text-emerald-600 dark:text-emerald-400 font-bold text-xl leading-none">&times;</button>
    </div>
@endif
@if ($errors->any())
    <div id="flash-err" class="bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/50 p-4">
        <div class="flex items-center justify-between mb-2">
            <span
                class="text-rose-700 dark:text-rose-400 font-bold text-xs font-mono uppercase tracking-wider">Import
                Gagal</span>
            <button onclick="this.parentElement.remove()"
                class="text-rose-500 dark:text-rose-400 font-bold text-xl leading-none">&times;</button>
        </div>
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $e)
                <li class="text-xs text-rose-700 dark:text-rose-400">{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ── Alpine.js Alert Responses ────────────────────────────────────────── --}}
<div x-show="successMessage" x-transition x-cloak
    class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-300 dark:border-emerald-700/50 p-4 flex items-center justify-between gap-3 rounded-none">
    <div class="flex items-center gap-2 text-emerald-800 dark:text-emerald-400 text-sm font-semibold">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span x-text="successMessage"></span>
    </div>
    <button @click="successMessage = ''"
        class="text-emerald-600 dark:text-emerald-400 font-bold text-xl leading-none">&times;</button>
</div>

<div x-show="errorMessage" x-transition x-cloak
    class="bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/50 p-4 rounded-none">
    <div class="flex items-center justify-between mb-2">
        <span
            class="text-rose-700 dark:text-rose-400 font-bold text-xs font-mono uppercase tracking-wider">Gagal Mengimpor Sebagian Data</span>
        <button @click="errorMessage = ''"
            class="text-rose-500 dark:text-rose-400 font-bold text-xl leading-none">&times;</button>
    </div>
    <p class="text-xs text-rose-700 dark:text-rose-400 mb-3" x-text="errorMessage"></p>
    
    {{-- List of Failed Rows & Explanations --}}
    <div class="max-h-40 overflow-y-auto border border-rose-100 dark:border-rose-900 bg-white/40 dark:bg-black/20 p-3 space-y-2">
        <template x-for="(failed, idx) in failedRows" :key="idx">
            <div class="text-[11px] font-mono text-rose-700 dark:text-rose-300">
                <span class="font-bold">Baris <span x-text="failed.row_number"></span> (<span x-text="failed.judul || 'Tanpa Judul'"></span>):</span>
                <ul class="list-disc pl-4 mt-0.5 space-y-0.5">
                    <template x-for="err in failed.errors">
                        <li x-text="err"></li>
                    </template>
                </ul>
            </div>
        </template>
    </div>
</div>
