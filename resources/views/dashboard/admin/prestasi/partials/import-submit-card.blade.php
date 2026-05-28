<div x-show="rows.length > 0" x-cloak class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
    <div class="px-5 py-3 border-b border-slate-100 dark:border-zinc-800 flex items-center gap-2">
        <span
            class="text-xs font-bold font-mono uppercase tracking-wider text-slate-700 dark:text-zinc-300">Proses
            Import</span>
    </div>
    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed">
            <template x-if="validCount === 0">
                <span class="text-rose-600 dark:text-rose-400 font-semibold">Semua baris memiliki data tidak valid atau kosong. Perbaiki terlebih dahulu.</span>
            </template>
            <template x-if="invalidCount > 0 && validCount > 0">
                <span class="text-amber-700 dark:text-amber-400">
                    <strong><span x-text="validCount"></span></strong> baris siap diimport.
                    <strong class="text-rose-600 dark:text-rose-400"><span x-text="invalidCount"></span></strong> baris belum lengkap (ditandai border kuning di kiri).
                </span>
            </template>
            <template x-if="invalidCount === 0 && validCount > 0">
                <span class="text-emerald-700 dark:text-emerald-400 font-semibold">
                    Semua <strong><span x-text="validCount"></span></strong> baris siap diimport ke database.
                </span>
            </template>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button type="button" @click="clearAll()"
                class="py-2.5 px-5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all font-mono">
                BATAL
            </button>
            <button id="btn-submit" type="button" @click="submitImport()" :disabled="validCount === 0 || loading"
                class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#4f45b2]/90 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold text-xs rounded-none transition-all tracking-wider font-mono flex items-center gap-2">
                <template x-if="loading">
                    <svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                </template>
                <template x-if="!loading">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                    </svg>
                </template>
                <span x-text="failedRows.length > 0 ? 'REUPLOAD DATA TERKOREKSI' : 'PROSES IMPORT'"></span>
            </button>
        </div>
    </div>
</div>
