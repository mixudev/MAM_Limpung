<!-- Tab 1: Connection Settings -->
<div x-show="activeTab === 'connection'" class="space-y-6 animate-fadeIn">
    <!-- Spreadsheet ID -->
    <div >
        <label for="spreadsheet_id" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Google Spreadsheet ID</label>
        <input type="text" name="spreadsheet_id" id="spreadsheet_id" value="{{ old('spreadsheet_id', $settings['spreadsheet_id']) }}" required class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] font-mono" placeholder="Contoh: 1aBcDeFgHiJkLmNoPqRsTuVwXyZ1234567890">
        @error('spreadsheet_id')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Centralized Google Service Account Credentials Status -->
    <div class="p-4 bg-slate-50 dark:bg-zinc-800/40 border border-slate-200 dark:border-zinc-700">
        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Kredensial Service Account (Terpusat)</span>
        
        <div class="flex items-center gap-3">
            @if($hasCredentials)
                <div class="px-2 py-1 text-[10px] font-bold font-mono tracking-wide uppercase bg-emerald-100 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/40">
                    Ready
                </div>
                <div class="text-xs text-slate-600 dark:text-zinc-450 font-medium">
                    Email: <span class="font-mono bg-slate-100 dark:bg-zinc-800 px-1 py-0.5 rounded">{{ $clientEmail }}</span>
                </div>
            @else
                <div class="px-2 py-1 text-[10px] font-bold font-mono tracking-wide uppercase bg-red-100 dark:bg-red-950/40 text-red-800 dark:text-red-400 border border-red-200 dark:border-red-900/40">
                    Belum Diatur
                </div>
                <div class="text-xs text-slate-500 dark:text-zinc-400">
                    Silakan atur kredensial JSON terlebih dahulu.
                </div>
            @endif
        </div>
        
        <div class="mt-3.5 pt-3 border-t border-slate-200/60 dark:border-zinc-700/60 flex items-center justify-between">
            <span class="text-[11px] text-slate-400 dark:text-zinc-500">
                Pengaturan Google Service Account di halaman Keamanan.
            </span>
            <a href="{{ route('admin.security.index') }}" class="text-[10px] font-mono font-bold uppercase tracking-wide text-[#4f45b2] dark:text-[#8c84c8] hover:underline flex items-center gap-1">
                Cek Pengaturan &rarr;
            </a>
        </div>
    </div>
</div>
