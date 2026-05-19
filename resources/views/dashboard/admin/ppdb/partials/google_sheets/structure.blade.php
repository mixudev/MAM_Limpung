<!-- Tab 2: Tab Structure & Custom Sheet Names -->
<div x-show="activeTab === 'structure'" class="space-y-6 animate-fadeIn" style="display: none;">
    <!-- Split Tab Mode Toggle -->
    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-zinc-850/40 border border-slate-200 dark:border-zinc-800">
        <div>
            <label for="split_by_status" class="text-sm font-bold text-slate-800 dark:text-white block cursor-pointer">Pisahkan Berdasarkan Status Seleksi (Multi-Tab)</label>
            <span class="text-xs text-slate-400 dark:text-zinc-500 block mt-0.5">Jika aktif, data calon siswa otomatis dipisah ke tab lembar kerja terpisah sesuai kelulusan mereka.</span>
        </div>
        <label class="relative inline-flex items-center cursor-pointer select-none">
            <input type="checkbox" name="split_by_status" id="split_by_status" value="1" {{ $settings['split_by_status'] ? 'checked' : '' }} class="sr-only peer">
            <div class="w-11 h-6 bg-slate-200 dark:bg-zinc-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#4f45b2]"></div>
        </label>
    </div>

    <!-- Active Sheets Toggle Checklist -->
    <div>
        <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-3">Pilih Lembar Kerja (Tab/Sheet) yang Ingin Ditampilkan</label>
        
        <div class="space-y-4">
            @php
                $availableSheets = [
                    'semua' => ['label' => 'Semua Pendaftar (Seluruh Calon Siswa)', 'desc' => 'Menampilkan daftar lengkap seluruh siswa yang mendaftar.'],
                    'diterima' => ['label' => 'Siswa Diterima', 'desc' => 'Menampilkan daftar khusus calon siswa yang dinyatakan diterima.'],
                    'pending' => ['label' => 'Dalam Proses (Seleksi)', 'desc' => 'Menampilkan daftar calon siswa yang status seleksinya masih ditinjau.'],
                    'ditolak' => ['label' => 'Siswa Ditolak', 'desc' => 'Menampilkan daftar calon siswa yang dinyatakan ditolak.'],
                    'ringkasan' => ['label' => 'Ringkasan Data & Statistik (Summary Dashboard)', 'desc' => 'Menampilkan statistik pendaftaran, profil rasio gender, dan sekolah asal terpopuler secara visual.'],
                ];
            @endphp

            @foreach($availableSheets as $key => $sheetInfo)
            <div class="p-4 bg-slate-50 dark:bg-zinc-855/40 border border-slate-200 dark:border-zinc-800/80 flex items-start gap-4">
                <input type="checkbox" name="active_sheets[]" value="{{ $key }}" id="sheet_active_{{ $key }}" {{ in_array($key, $settings['active_sheets'] ?? []) ? 'checked' : '' }} class="mt-1 rounded-none text-[#4f45b2] focus:ring-[#4f45b2]/40 border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-850 w-4 h-4">
                <div class="flex-1">
                    <label for="sheet_active_{{ $key }}" class="text-xs font-bold text-slate-800 dark:text-white cursor-pointer block">{{ $sheetInfo['label'] }}</label>
                    <span class="text-[11px] text-slate-400 dark:text-zinc-500 mt-0.5 block leading-relaxed">{{ $sheetInfo['desc'] }}</span>
                    
                    <!-- Custom Sheet Name Input -->
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div>
                            <label class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1">Nama Tab di Google Sheet</label>
                            <input type="text" name="sheet_names[{{ $key }}]" value="{{ old('sheet_names.'.$key, $settings['sheet_names'][$key] ?? '') }}" placeholder="Contoh: {{ $settings['sheet_names'][$key] ?? '' }}" class="w-full py-1.5 px-3 text-xs bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
