<div id="step-2" x-show="step === 2" class="space-y-6" style="display: none;">
    <div class="border-b border-slate-100 pb-3 mb-6">
        <h2 class="text-base font-bold text-slate-800 uppercase tracking-wider font-mono">Langkah 2: Kontak & Wali Orang Tua</h2>
        <p class="text-xs text-slate-400 mt-1">Lengkapi data komunikasi utama dan nama orang tua kandung pendaftar.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="form-label-premium">Nomor HP / WhatsApp *</label>
            <input type="tel" name="nomor_hp" x-model="form.nomor_hp" class="form-input-premium @error('nomor_hp') !border-red-500 @enderror" placeholder="Contoh: 08123456789" required>
            @error('nomor_hp')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="form-label-premium">Email Aktif *</label>
            <input type="email" name="email" x-model="form.email" class="form-input-premium @error('email') !border-red-500 @enderror" placeholder="contoh@email.com" required>
            @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label class="form-label-premium">Nama Sekolah Asal (SMP/MTs) *</label>
            <input type="text" name="sekolah_asal" x-model="form.sekolah_asal" class="form-input-premium @error('sekolah_asal') !border-red-500 @enderror" placeholder="Masukkan nama sekolah asal lengkap" required>
            @error('sekolah_asal')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="form-label-premium">Nama Ayah Kandung *</label>
            <input type="text" name="nama_ayah" x-model="form.nama_ayah" class="form-input-premium @error('nama_ayah') !border-red-500 @enderror" placeholder="Nama lengkap ayah sesuai berkas" required>
            @error('nama_ayah')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="form-label-premium">Nama Ibu Kandung *</label>
            <input type="text" name="nama_ibu" x-model="form.nama_ibu" class="form-input-premium @error('nama_ibu') !border-red-500 @enderror" placeholder="Nama lengkap ibu sesuai berkas" required>
            @error('nama_ibu')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
