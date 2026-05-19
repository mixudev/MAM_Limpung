<div>
    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider border-b border-slate-100 dark:border-zinc-800 pb-3 mb-4 flex items-center gap-2">
        <span class="w-2 h-4 bg-[#4f45b2]"></span>
        Data Orang Tua & Alamat
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Nama Ayah Kandung -->
        <div>
            <label for="nama_ayah" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Nama Ayah Kandung <span class="text-red-500">*</span></label>
            <input type="text" name="nama_ayah" id="nama_ayah" value="{{ old('nama_ayah') }}" required
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] @error('nama_ayah') border-red-500 @enderror"
                placeholder="Masukkan nama ayah kandung">
            @error('nama_ayah')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nama Ibu Kandung -->
        <div>
            <label for="nama_ibu" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Nama Ibu Kandung <span class="text-red-500">*</span></label>
            <input type="text" name="nama_ibu" id="nama_ibu" value="{{ old('nama_ibu') }}" required
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] @error('nama_ibu') border-red-500 @enderror"
                placeholder="Masukkan nama ibu kandung">
            @error('nama_ibu')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Alamat Lengkap -->
        <div class="md:col-span-2">
            <label for="alamat_lengkap" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Alamat Lengkap Domisili <span class="text-red-500">*</span></label>
            <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3" required
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] @error('alamat_lengkap') border-red-500 @enderror"
                placeholder="Masukkan alamat lengkap RT/RW, Dusun, Desa, Kecamatan, Kabupaten">{{ old('alamat_lengkap') }}</textarea>
            @error('alamat_lengkap')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
