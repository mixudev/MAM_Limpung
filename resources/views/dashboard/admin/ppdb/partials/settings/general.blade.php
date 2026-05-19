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

                <!-- Target Kuota -->
                <div>
                    <label for="target_quota" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Target Kuota Tampung (Siswa)</label>
                    <input type="number" name="target_quota" id="target_quota" value="{{ $general['target_quota'] }}" required min="1" max="5000"
                        class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                </div>

                <!-- Biaya Pendaftaran -->
                <div>
                    <label for="registration_fee" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Biaya Registrasi Pendaftaran (Rp)</label>
                    <input type="number" name="registration_fee" id="registration_fee" value="{{ $general['registration_fee'] }}" required min="0"
                        class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                </div>

                <!-- Tanggal Mulai -->
                <div>
                    <label for="start_date" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Tanggal Mulai Pendaftaran</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $general['start_date'] }}" required
                        class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                </div>

                <!-- Tanggal Tutup -->
                <div>
                    <label for="end_date" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Tanggal Selesai Pendaftaran</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $general['end_date'] }}" required
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
</div>
