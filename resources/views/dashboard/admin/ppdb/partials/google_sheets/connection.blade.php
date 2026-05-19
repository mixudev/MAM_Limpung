<!-- Tab 1: Connection Settings -->
<div x-show="activeTab === 'connection'" class="space-y-6 animate-fadeIn">
    <!-- Spreadsheet ID -->
    <div>
        <label for="spreadsheet_id" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Google Spreadsheet ID</label>
        <input type="text" name="spreadsheet_id" id="spreadsheet_id" value="{{ old('spreadsheet_id', $settings['spreadsheet_id']) }}" required class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] font-mono" placeholder="Contoh: 1aBcDeFgHiJkLmNoPqRsTuVwXyZ1234567890">
        @error('spreadsheet_id')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Service Account Credentials (JSON) -->
    <div>
        <label for="service_account_json" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Kredensial Service Account (format JSON)</label>
        <textarea name="service_account_json" id="service_account_json" rows="8" class="w-full py-2.5 px-3 text-xs bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] font-mono" placeholder="Tempel/paste isi file JSON Service Account Anda di sini secara utuh...">{{ old('service_account_json', $maskedJson) }}</textarea>
        <div class="mt-2 flex items-center justify-between">
            <p class="text-[11px] text-slate-400 dark:text-zinc-500">
                @if($hasCredentials)
                <span class="text-emerald-600 dark:text-emerald-500 font-semibold">Kredensial sudah tersimpan dengan sangat aman (Terenkripsi).</span> Kosongkan atau biarkan teks bertanda aman jika tidak ingin mengubahnya.
                @else
                <span class="text-amber-600 dark:text-amber-500 font-semibold">Kredensial belum dikonfigurasi.</span> Tempelkan file JSON Anda untuk memulai.
                @endif
            </p>
            <button type="button" onclick="clearJsonTextarea()" class="text-[9px] font-mono font-bold uppercase tracking-wide text-red-500 hover:underline">Hapus Input</button>
        </div>
        @error('service_account_json')
        <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
        @enderror
    </div>
</div>
