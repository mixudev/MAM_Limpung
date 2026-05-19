<div>
    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider border-b border-slate-100 dark:border-zinc-800 pb-3 mb-4 flex items-center gap-2">
        <span class="w-2 h-4 bg-[#4f45b2]"></span>
        Kontak & Asal Sekolah
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Nomor HP / WhatsApp -->
        <div>
            <label for="nomor_hp" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
            <input type="text" name="nomor_hp" id="nomor_hp" value="{{ old('nomor_hp') }}" required
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] @error('nomor_hp') border-red-500 @enderror"
                placeholder="Contoh: 08123456789">
            @error('nomor_hp')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Alamat Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] @error('email') border-red-500 @enderror"
                placeholder="Masukkan email unik calon siswa">
            @error('email')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
            <span class="text-[9px] text-slate-400 dark:text-zinc-500 block mt-1">Gunakan format `nisn@mamlimpung.sch.id` bila siswa tidak memiliki email pribadi.</span>
        </div>

        <!-- Sekolah Asal -->
        <div>
            <label for="sekolah_asal" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Sekolah Asal <span class="text-red-500">*</span></label>
            <input type="text" name="sekolah_asal" id="sekolah_asal" value="{{ old('sekolah_asal') }}" required
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] @error('sekolah_asal') border-red-500 @enderror"
                placeholder="SMP / MTs asal siswa">
            @error('sekolah_asal')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Ukuran Baju Olahraga -->
        <div>
            <label for="ukuran_baju" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Ukuran Baju Seragam <span class="text-red-500">*</span></label>
            <select name="ukuran_baju" id="ukuran_baju" required
                class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] @error('ukuran_baju') border-red-500 @enderror">
                <option value="" disabled selected>Pilih Ukuran Baju</option>
                @foreach(['S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                    <option value="{{ $size }}" {{ old('ukuran_baju') == $size ? 'selected' : '' }}>Ukuran {{ $size }}</option>
                @endforeach
            </select>
            @error('ukuran_baju')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
