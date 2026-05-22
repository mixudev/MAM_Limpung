<!-- Tab 2: Persyaratan Berkas -->
<div id="tab-persyaratan" class="tab-content space-y-6 hidden">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-850 pb-3 mb-5">
            <div>
                <h3 class="text-sm font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8]">
                    Daftar Berkas Syarat Unggah
                </h3>
                <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Kelola dokumen scan yang wajib diunggah oleh calon siswa baru pada formulir pendaftaran.</p>
            </div>
            <button type="button" onclick="addNewRequirementRow()" class="py-2 px-4 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-mono font-bold text-[10px] uppercase tracking-wider rounded-none">
                + Tambah Berkas Baru
            </button>
        </div>

        <form action="{{ route('admin.ppdb.settings.requirements') }}" method="POST" id="requirementsForm">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="requirementsTable">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-zinc-800/40 border-b border-slate-100 dark:border-zinc-800/80">
                            <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">ID Kode Berkas (Slug)</th>
                            <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Nama Dokumen Persyaratan</th>
                            <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Sifat Dokumen</th>
                            <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Status Tampil</th>
                            <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/50" id="requirementsTableBody">
                        @forelse($requirements as $index => $req)
                            <tr class="hover:bg-slate-50/20 transition-all" id="req-row-{{ $index }}">
                                <!-- ID / Key -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="text" name="requirements[{{ $index }}][id]" value="{{ $req['id'] }}" required readonly
                                        class="w-full bg-slate-100 dark:bg-zinc-800/80 border border-slate-200 dark:border-zinc-700 rounded-none text-xs font-mono text-slate-500 dark:text-zinc-400 py-1.5 px-2.5">
                                </td>
                                <!-- Label -->
                                <td class="px-4 py-3">
                                    <input type="text" name="requirements[{{ $index }}][label]" value="{{ $req['label'] }}" required
                                        class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                                </td>
                                <!-- Mandatory / Optional Toggle -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <select name="requirements[{{ $index }}][required]" 
                                        class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2">
                                        <option value="1" {{ $req['required'] ? 'selected' : '' }}>WAJIB (Required)</option>
                                        <option value="0" {{ !$req['required'] ? 'selected' : '' }}>OPSIONAL (Optional)</option>
                                    </select>
                                </td>
                                <!-- Is Active Toggle -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <select name="requirements[{{ $index }}][is_active]" 
                                        class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2">
                                        <option value="1" {{ ($req['is_active'] ?? true) ? 'selected' : '' }}>TAMPILKAN (Ya)</option>
                                        <option value="0" {{ !($req['is_active'] ?? true) ? 'selected' : '' }}>SEMBUNYIKAN (Tidak)</option>
                                    </select>
                                </td>
                                <!-- Delete Row -->
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <button type="button" onclick="removeRequirementRow('{{ $index }}')" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-100/50 rounded-none transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-requirements-placeholder">
                                <td colspan="5" class="text-center py-8 text-slate-400 dark:text-zinc-500 text-xs">Belum ada persyaratan berkas yang didaftarkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 dark:border-zinc-850 pt-4 flex justify-end mt-4">
                <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.98]">
                    Simpan Daftar Persyaratan
                </button>
            </div>
        </form>
    </div>
</div>
