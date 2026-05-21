<div id="step-1" x-show="step === 1" class="space-y-6">
    <div class="border-b border-slate-100 pb-3 mb-6">
        <h2 class="text-base font-bold text-slate-800 uppercase tracking-wider font-mono">Langkah 1: Profil Calon Siswa</h2>
        <p class="text-xs text-slate-400 mt-1">Lengkapi biodata diri utama Anda sesuai dokumen resmi keluarga.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="form-label-premium">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" x-model="form.nama_lengkap" class="form-input-premium @error('nama_lengkap') !border-red-500 @enderror" placeholder="Masukkan nama lengkap sesuai ijazah/akta" required>
                    @error('nama_lengkap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label-premium">NISN *</label>
                    <input type="text" name="nisn" x-model="form.nisn" class="form-input-premium @error('nisn') !border-red-500 @enderror" placeholder="10 digit nomor NISN" required>
                    @error('nisn')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label-premium">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" x-model="form.jenis_kelamin" class="form-input-premium @error('jenis_kelamin') !border-red-500 @enderror" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label-premium">Tempat Lahir *</label>
                    <input type="text" name="tempat_lahir" x-model="form.tempat_lahir" class="form-input-premium @error('tempat_lahir') !border-red-500 @enderror" placeholder="Kota kelahiran" required>
                    @error('tempat_lahir')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label-premium">Tanggal Lahir *</label>
                    <input type="date" name="tanggal_lahir" x-model="form.tanggal_lahir" class="form-input-premium @error('tanggal_lahir') !border-red-500 @enderror" required>
                    @error('tanggal_lahir')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="form-label-premium">Ukuran Seragam *</label>
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                        @foreach(['S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                        <label class="flex items-center justify-center rounded-none cursor-pointer">
                            <input type="radio" name="ukuran_baju" value="{{ $size }}" x-model="form.ukuran_baju" class="hidden peer" required>
                            <div class="border border-slate-200 peer-checked:border-emerald-800 peer-checked:bg-emerald-50 peer-checked:text-emerald-800 w-full text-center font-bold p-2 text-xs uppercase tracking-wider transition-all">
                                {{ $size }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('ukuran_baju')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="form-label-premium">Alamat Lengkap *</label>
                    <textarea name="alamat_lengkap" x-model="form.alamat_lengkap" rows="3" class="form-input-premium resize-none @error('alamat_lengkap') !border-red-500 @enderror" placeholder="Masukkan alamat RT/RW, Dusun, Kelurahan, Kecamatan" required></textarea>
                    @error('alamat_lengkap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="border border-slate-200 p-5 bg-slate-50 sticky top-4 @error('foto_siswa') border-red-500 @enderror">
                <label class="block text-xs font-bold text-slate-800 uppercase tracking-widest font-mono mb-3">Pas Foto Resmi *</label>

                @php $fotoTemp = $ppdbTempUploads['foto_siswa'] ?? null; @endphp
                <div id="dropZone" class="min-h-[220px] border-2 border-dashed border-slate-300 flex flex-col items-center justify-center cursor-pointer bg-white p-4 hover:border-emerald-800 transition-colors">
                    <div id="dropZoneContent" class="text-center"
                        @if($fotoTemp)
                            data-restored-url="{{ $fotoTemp['url'] }}"
                            data-restored-name="{{ $fotoTemp['original_name'] }}"
                        @endif>
                        @unless($fotoTemp)
                        <svg class="w-10 h-10 text-slate-400 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-slate-700 text-xs font-bold mb-1">Unggah Pas Foto</p>
                        <p class="text-slate-400 text-[10px] mb-3">Seret & lepas berkas di sini</p>
                        <button type="button" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border border-slate-355 text-slate-700 text-[10px] font-bold uppercase tracking-wider transition-colors">Pilih File</button>
                        @endunless
                    </div>
                </div>
                <input type="file" name="foto_siswa" id="fileInput" class="hidden" accept="image/*" {{ $fotoTemp ? '' : 'required' }}>
                @if($fotoTemp)
                <p class="text-[10px] text-emerald-800 font-bold mt-2">Foto tersimpan dari unggahan sebelumnya. Klik area foto untuk mengganti.</p>
                @endif

                <div class="mt-4 space-y-2">
                    <p class="text-[10px] text-slate-500 leading-normal">Gunakan pas foto resmi berseragam dengan latar belakang merah atau biru.</p>
                    <p class="text-[10px] text-slate-500 leading-normal">Maksimal 2MB, format file .jpg, .jpeg, .png</p>
                </div>
                @error('foto_siswa')
                <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>
