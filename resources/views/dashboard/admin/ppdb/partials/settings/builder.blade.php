<!-- Tab 3: Pembangun Formulir -->
<div id="tab-formulir" class="tab-content space-y-6 hidden">
    
    <!-- Core Database Form Fields Info (Core / Reserved) -->
    <div class="bg-slate-50 dark:bg-zinc-950 p-6 border border-slate-200 dark:border-zinc-850 rounded-none">
        <h4 class="text-xs font-mono font-bold uppercase tracking-wider text-[#4f45b2] dark:text-[#8c84c8] flex items-center gap-2 mb-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
            Kolom Formulir Utama (Core System Fields)
        </h4>
        <p class="text-xs text-slate-500 dark:text-zinc-400 mb-4">Input berikut adalah kolom bawaan sistem data administrasi terpusat. Kolom ini bersifat permanen demi menjamin kecocokan laporan database.</p>
        <div class="flex flex-wrap gap-2">
            @php
                $cores = ['Nama Lengkap', 'NISN', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Nomor HP', 'Email', 'Nama Ayah', 'Nama Ibu', 'Alamat Lengkap', 'Sekolah Asal', 'Ukuran Seragam', 'Pratinjau Foto'];
            @endphp
            @foreach($cores as $core)
                <span class="px-2.5 py-1 text-[10px] font-mono border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-400 dark:text-zinc-500 rounded-none">
                    {{ $core }}
                </span>
            @endforeach
        </div>
    </div>

    <!-- Custom Fields Workspace (Interactive Table-Edit Batch) -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-850 pb-3 mb-5">
            <div>
                <h3 class="text-sm font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8]">
                    Daftar Kolom Kustom Dinamis
                </h3>
                <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Buat kolom isian tambahan sesuai kebutuhan sekolah Anda dan akan terintegrasi langsung di form pendaftaran siswa.</p>
            </div>
            <button type="button" onclick="addNewFormFieldRow()" class="py-2 px-4 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-mono font-bold text-[10px] uppercase tracking-wider rounded-none">
                + Tambah Kolom Kustom
            </button>
        </div>

        <form action="{{ route('admin.ppdb.settings.fields.update') }}" method="POST" id="formFieldsForm">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="formFieldsTable">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-zinc-800/40 border-b border-slate-100 dark:border-zinc-800/80">
                            <th class="px-2 py-3.5 w-10 text-center text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500"></th>
                            <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Label Pertanyaan / Input</th>
                            <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Tipe Input</th>
                            <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Sifat</th>
                            <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Status Tampil</th>
                            <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/50" id="formFieldsTableBody">
                        @forelse($formFields as $index => $field)
                            <tr class="hover:bg-slate-50/20 transition-all cursor-move" id="field-row-{{ $index }}" draggable="true">
                                <!-- Drag Handle -->
                                <td class="drag-handle px-2 py-3 text-center whitespace-nowrap text-slate-455 dark:text-zinc-650 cursor-grab active:cursor-grabbing">
                                    <svg class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </td>
                                <!-- ID Slug Hidden -->
                                <input type="hidden" name="fields[{{ $index }}][id]" value="{{ $field['id'] }}">
                                <!-- Label & Options -->
                                <td class="px-4 py-3">
                                    <div class="space-y-2">
                                        <input type="text" name="fields[{{ $index }}][label]" id="field-label-input-{{ $index }}" value="{{ $field['label'] }}" required
                                            oninput="syncFieldSlug({{ $index }})"
                                            class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                                        
                                        <div id="field-options-wrapper-{{ $index }}" class="{{ $field['type'] !== 'select' ? 'hidden' : '' }}">
                                            <span class="text-[10px] font-mono font-bold text-slate-400 dark:text-zinc-500 block mb-1">PILIHAN DROPDOWN (Pisahkan dengan koma):</span>
                                            <input type="text" name="fields[{{ $index }}][options]" id="field-options-input-{{ $index }}"
                                                value="{{ !empty($field['options']) ? implode(', ', $field['options']) : '' }}"
                                                placeholder="e.g. Pilihan 1, Pilihan 2, Pilihan 3"
                                                class="w-full bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 rounded-none text-xs py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                                        </div>
                                    </div>
                                </td>
                                <!-- Type -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <select name="fields[{{ $index }}][type]" id="field-type-select-{{ $index }}" onchange="toggleOptionsInputRow({{ $index }})"
                                        class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2">
                                        <option value="text" {{ $field['type'] === 'text' ? 'selected' : '' }}>Teks Singkat</option>
                                        <option value="number" {{ $field['type'] === 'number' ? 'selected' : '' }}>Angka</option>
                                        <option value="select" {{ $field['type'] === 'select' ? 'selected' : '' }}>Dropdown Menu</option>
                                        <option value="date" {{ $field['type'] === 'date' ? 'selected' : '' }}>Tanggal</option>
                                        <option value="textarea" {{ $field['type'] === 'textarea' ? 'selected' : '' }}>Teks Panjang</option>
                                    </select>
                                </td>
                                <!-- Required Toggle -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <select name="fields[{{ $index }}][required]" 
                                        class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2">
                                        <option value="0" {{ !$field['required'] ? 'selected' : '' }}>OPSIONAL</option>
                                        <option value="1" {{ $field['required'] ? 'selected' : '' }}>WAJIB</option>
                                    </select>
                                </td>
                                <!-- Is Active Toggle -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <select name="fields[{{ $index }}][is_active]" 
                                        class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2">
                                        <option value="1" {{ ($field['is_active'] ?? true) ? 'selected' : '' }}>TAMPILKAN (Ya)</option>
                                        <option value="0" {{ !($field['is_active'] ?? true) ? 'selected' : '' }}>SEMBUNYIKAN (Tidak)</option>
                                    </select>
                                </td>
                                <!-- Delete Row -->
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <button type="button" onclick="removeFormFieldRow('{{ $index }}', '{{ $field['label'] }}')" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-100/50 rounded-none transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-fields-placeholder">
                                <td colspan="7" class="text-center py-10 text-slate-400 dark:text-zinc-500 text-xs">Belum ada kolom kustom tambahan. Formulir pendaftaran hanya memuat kolom inti.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 dark:border-zinc-850 pt-4 flex justify-end mt-4">
                <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.98]">
                    Simpan Daftar Kolom Kustom
                </button>
            </div>
        </form>
    </div>
</div>
