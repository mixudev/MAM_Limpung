<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
    <div class="px-5 py-3 border-b border-slate-100 dark:border-zinc-800 flex items-center gap-2">
        <span
            class="text-xs font-bold font-mono uppercase tracking-wider text-slate-700 dark:text-zinc-300">Unduh
            Template Excel</span>
    </div>
    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed">
            Gunakan template resmi kami. Isi data mulai <strong
                class="text-slate-700 dark:text-zinc-300">baris ke-5</strong>. Jangan ubah baris header
            (1–4).
        </p>
        <a href="{{ route('admin.prestasi.template') }}"
            class="shrink-0 inline-flex items-center gap-2 py-2.5 px-5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-none transition-all tracking-wider font-mono">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            DOWNLOAD TEMPLATE
        </a>
    </div>
</div>
