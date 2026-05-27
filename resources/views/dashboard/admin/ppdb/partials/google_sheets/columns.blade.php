<!-- Tab 3: Column Fields -->
<div x-show="activeTab === 'columns'" class="space-y-6 animate-fadeIn" style="display: none;">
    <div>
        <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-3">Kolom Data yang Disinkronkan</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @php
                $fieldsList = [
                    'no_registrasi' => 'No. Registrasi',
                    'nama_lengkap' => 'Nama Lengkap',
                    'nisn' => 'NISN',
                    'jenis_kelamin' => 'Jenis Kelamin',
                    'sekolah_asal' => 'Sekolah Asal',
                    'no_hp' => 'No. HP / WA',
                    'email' => 'Email',
                    'status' => 'Status Seleksi',
                    'tanggal_daftar' => 'Tanggal Daftar',
                    'custom_fields' => 'Formulir Kustom Tambahan'
                ];
            @endphp
            @foreach($fieldsList as $key => $label)
            <label class="flex items-center gap-3 p-3 bg-zinc-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-800 cursor-pointer hover:bg-slate-100/50 dark:hover:bg-zinc-800/40 transition-all select-none">
                <input type="checkbox" name="sync_fields[]" value="{{ $key }}" {{ in_array($key, $settings['sync_fields'] ?? []) ? 'checked' : '' }} class="rounded-none text-[#4f45b2] focus:ring-[#4f45b2]/40 border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 w-4 h-4">
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300">{{ $label }}</span>
            </label>
            @endforeach
        </div>
        @error('sync_fields')
        <p class="text-red-550 text-xs mt-2 font-semibold">{{ $message }}</p>
        @enderror
    </div>
</div>
