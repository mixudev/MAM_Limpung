<!-- Secure Metadata Info Card -->
<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-5 shadow-sm">
    <h4 class="text-xs font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8] border-b border-slate-100 dark:border-zinc-850 pb-2 mb-3">
        Info Kredensial Aktif
    </h4>
    <div class="space-y-3">
        <div>
            <span class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-wider">Email Akun Layanan</span>
            <div class="flex items-center gap-2 mt-1">
                <span id="active-email-badge" class="px-2 py-1 bg-slate-100 dark:bg-zinc-800 text-[10px] font-mono text-slate-700 dark:text-zinc-300 font-bold block truncate max-w-full" title="{{ $clientEmail }}">
                    {{ $clientEmail }}
                </span>
                @if($hasCredentials && $clientEmail !== '-')
                <button onclick="copyToClipboard('{{ $clientEmail }}')" class="text-[#4f45b2] dark:text-[#8c84c8] hover:text-[#4f45b2]/75 flex-shrink-0" title="Salin Email">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                    </svg>
                </button>
                @endif
            </div>
        </div>
        <div>
            <span class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-wider">Proteksi Keamanan</span>
            <div class="flex items-center gap-2 mt-1 text-xs text-slate-600 dark:text-zinc-400 leading-normal">
                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Enkripsi <strong>AES-256-CBC</strong> didukung Laravel Native Crypt. Kredensial Anda 100% terlindung dari eksploitasi kebocoran database.</span>
            </div>
        </div>
    </div>
</div>
