<!-- Tab 1: Umum & Alur PPDB -->
<div id="tab-umum" class="tab-content space-y-6">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none shadow-sm">
        <h3 class="text-sm font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8] border-b border-slate-100 dark:border-zinc-850 pb-3 mb-5">
            Konfigurasi Umum Penerimaan
        </h3>

        <form action="{{ route('admin.ppdb.settings.general') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status Switch -->
                <div>
                    <label for="is_open" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Status Gerbang PPDB</label>
                    <select name="is_open" id="is_open" class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                        <option value="1" {{ $general['is_open'] ? 'selected' : '' }}>BUKA - Pendaftaran Aktif</option>
                        <option value="0" {{ !$general['is_open'] ? 'selected' : '' }}>TUTUP - Pendaftaran Nonaktif</option>
                    </select>
                </div>

                <!-- Tahun Pelajaran -->
                <div>
                    <label for="tahun_ajaran" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Tahun Pelajaran Berjalan</label>
                    <input type="number" name="tahun_ajaran" id="tahun_ajaran" value="{{ $general['tahun_ajaran'] }}" required min="2020" max="2100"
                        class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                </div>
            </div>

            <div class="border-t border-slate-100 dark:border-zinc-850 pt-4 flex justify-end">
                <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.98]">
                    Simpan Pengaturan Umum
                </button>
            </div>
        </form>
    </div>

    <!-- Waves Configuration -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none shadow-sm mt-6">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-850 pb-3 mb-5">
            <div>
                <h3 class="text-sm font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8]">
                    Gelombang Pendaftaran
                </h3>
                <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Kelola jadwal gelombang pendaftaran secara fleksibel.</p>
            </div>
            <button type="button" onclick="addNewWaveRow()" class="py-2 px-4 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-mono font-bold text-[10px] uppercase tracking-wider rounded-none">
                + Tambah Gelombang
            </button>
        </div>

        <form action="{{ route('admin.ppdb.settings.waves') }}" method="POST" id="wavesForm">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="wavesTable">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-zinc-800/40 border-b border-slate-100 dark:border-zinc-800/80">
                            <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">ID Kode (Slug)</th>
                            <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Nama Gelombang</th>
                            <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Tanggal Mulai</th>
                            <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Tanggal Selesai</th>
                            <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/50" id="wavesTableBody">
                        @forelse($waves as $index => $wave)
                            <tr class="hover:bg-slate-50/20 transition-all" id="wave-row-{{ $index }}">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="text" name="waves[{{ $index }}][id]" value="{{ $wave['id'] }}" required readonly
                                        class="w-full bg-slate-100 dark:bg-zinc-800/80 border border-slate-200 dark:border-zinc-700 rounded-none text-xs font-mono text-slate-500 dark:text-zinc-400 py-1.5 px-2.5">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="waves[{{ $index }}][name]" value="{{ $wave['name'] }}" required
                                        class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="date" name="waves[{{ $index }}][start_date]" value="{{ $wave['start_date'] }}" required
                                        class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="date" name="waves[{{ $index }}][end_date]" value="{{ $wave['end_date'] }}" required
                                        class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <button type="button" onclick="removeWaveRow('{{ $index }}')" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-100/50 rounded-none transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-waves-placeholder">
                                <td colspan="5" class="text-center py-8 text-slate-400 dark:text-zinc-500 text-xs">Belum ada gelombang pendaftaran yang diatur.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 dark:border-zinc-850 pt-4 flex justify-end mt-4">
                <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.98]">
                    Simpan Jadwal Gelombang
                </button>
            </div>
        </form>
    </div>
</div>
