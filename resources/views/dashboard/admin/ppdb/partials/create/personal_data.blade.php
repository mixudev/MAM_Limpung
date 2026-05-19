<div>
    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider border-b border-slate-100 dark:border-zinc-800 pb-3 mb-4 flex items-center gap-2">
        <span class="w-2 h-4 bg-[#4f45b2]"></span>
        Data Diri Calon Siswa
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Nama Lengkap -->
        <div class="md:col-span-2">
            <label for="nama_lengkap" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] @error('nama_lengkap') border-red-500 @enderror"
                placeholder="Masukkan nama lengkap siswa sesuai ijazah/akta">
            @error('nama_lengkap')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- NISN -->
        <div>
            <label for="nisn" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">NISN <span class="text-red-500">*</span></label>
            <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}" required maxlength="10"
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] @error('nisn') border-red-500 @enderror"
                placeholder="10 digit nomor NISN">
            @error('nisn')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Jenis Kelamin -->
        <div>
            <label for="jenis_kelamin" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Jenis Kelamin <span class="text-red-500">*</span></label>
            <select name="jenis_kelamin" id="jenis_kelamin" required
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] @error('jenis_kelamin') border-red-500 @enderror">
                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('jenis_kelamin')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tempat Lahir -->
        <div>
            <label for="tempat_lahir" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Tempat Lahir <span class="text-red-500">*</span></label>
            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" required
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] @error('tempat_lahir') border-red-500 @enderror"
                placeholder="Kabupaten/Kota lahir">
            @error('tempat_lahir')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tanggal Lahir -->
        <div>
            <label for="tanggal_lahir" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Tanggal Lahir <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] @error('tanggal_lahir') border-red-500 @enderror">
            @error('tanggal_lahir')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
