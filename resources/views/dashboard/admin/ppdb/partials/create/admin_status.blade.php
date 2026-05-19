<div>
    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider border-b border-slate-100 dark:border-zinc-800 pb-3 mb-4 flex items-center gap-2">
        <span class="w-2 h-4 bg-[#4f45b2]"></span>
        Status & Catatan Admin
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Status Pendaftaran (Col-span 1) -->
        <div class="md:col-span-1">
            <label for="status" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Status Pendaftaran Awal <span class="text-red-500">*</span></label>
            <select name="status" id="status" required
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] @error('status') border-red-500 @enderror">
                <option value="diterima" {{ old('status', 'diterima') === 'diterima' ? 'selected' : '' }}>Langsung Verifikasi & Terima</option>
                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu Verifikasi)</option>
                <option value="ditolak" {{ old('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            @error('status')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
            <span class="text-[9px] text-slate-400 dark:text-zinc-500 block mt-1">Gunakan "Langsung Verifikasi & Terima" jika pendaftaran fisik siswa langsung dinyatakan valid oleh petugas sekolah.</span>
        </div>

        <!-- Catatan Admin (Col-span 2) -->
        <div class="md:col-span-2">
            <label for="catatan_admin" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Catatan Admin</label>
            <textarea name="catatan_admin" id="catatan_admin" rows="4"
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]"
                placeholder="Tulis catatan penting pendaftaran ini (misal: dokumen fisik asli sudah lengkap, pembayaran biaya pendaftaran lunas, dll.)">{{ old('catatan_admin') }}</textarea>
            @error('catatan_admin')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
