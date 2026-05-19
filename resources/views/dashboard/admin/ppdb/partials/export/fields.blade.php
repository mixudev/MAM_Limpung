<!-- STEP 3: Fields Checklist selection -->
<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 dark:border-zinc-850 pb-3 mb-4 gap-2">
        <h3 class="text-xs font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8]">
            3. Pilih Kolom Data untuk Diexport
        </h3>
        <div class="flex items-center gap-3">
            <button type="button" onclick="toggleAllCheckboxes(true)" class="text-[10px] font-mono text-[#4f45b2] dark:text-[#8c84c8] hover:underline font-bold uppercase">Pilih Semua</button>
            <span class="text-slate-300 dark:text-zinc-700">|</span>
            <button type="button" onclick="toggleAllCheckboxes(false)" class="text-[10px] font-mono text-red-500 hover:underline font-bold uppercase">Hapus Semua</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-2">
        
        <!-- Kategori 1: Data Peserta Utama -->
        <div class="bg-slate-50/50 dark:bg-zinc-900/30 p-5 border border-slate-100 dark:border-zinc-800/80 space-y-3">
            <h4 class="text-xs font-mono font-bold uppercase tracking-wider text-[#4f45b2] dark:text-[#8c84c8] border-b border-slate-200 dark:border-zinc-800 pb-2 mb-3">
                A. Data Peserta Utama
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="nomor_registrasi" checked class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Nomor Registrasi</span>
                </label>
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="nama_lengkap" checked class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Nama Lengkap</span>
                </label>
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="nisn" checked class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>NISN</span>
                </label>
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="jenis_kelamin" checked class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Jenis Kelamin (L/P)</span>
                </label>
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="sekolah_asal" checked class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Sekolah Asal</span>
                </label>
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="nomor_hp" checked class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Nomor HP / WhatsApp</span>
                </label>
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="status" checked class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Status Verifikasi</span>
                </label>
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="submitted_at" class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Tanggal Pendaftaran</span>
                </label>
            </div>
        </div>

        <!-- Kategori 2: Data Detail Pendukung -->
        <div class="bg-slate-50/50 dark:bg-zinc-900/30 p-5 border border-slate-100 dark:border-zinc-800/80 space-y-3">
            <h4 class="text-xs font-mono font-bold uppercase tracking-wider text-[#4f45b2] dark:text-[#8c84c8] border-b border-slate-200 dark:border-zinc-800 pb-2 mb-3">
                B. Data Detail Pendukung
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="tempat_lahir" class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Tempat Lahir</span>
                </label>
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="tanggal_lahir" class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Tanggal Lahir</span>
                </label>
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="ukuran_baju" class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Ukuran Baju Olahraga</span>
                </label>
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="email" class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Email</span>
                </label>
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="alamat_lengkap" class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Alamat Rumah Lengkap</span>
                </label>
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="nama_ayah" class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Nama Ayah</span>
                </label>
                <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                    <input type="checkbox" name="fields[]" value="nama_ibu" class="accent-[#4f45b2] h-4 w-4 rounded-none">
                    <span>Nama Ibu</span>
                </label>
                
                <!-- Dynamic Custom Input Fields under Detail category -->
                @if(!empty($customFields))
                    @foreach($customFields as $field)
                        <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none">
                            <input type="checkbox" name="fields[]" value="{{ $field['id'] }}" class="accent-[#4f45b2] h-4 w-4 rounded-none">
                            <span>{{ $field['id'] === 'nama_wali' ? 'Nama Wali' : $field['label'] }}</span>
                        </label>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
</div>
