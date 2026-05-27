@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Tambah Prestasi';
        }
    });
</script>

<div class="max-w-4xl space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Tambah Prestasi Baru</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Tambahkan data prestasi akademik atau non-akademik siswa yang membanggakan.</p>
        </div>
        <a href="{{ route('admin.prestasi.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all text-center font-mono">
            KEMBALI
        </a>
    </div>

    <!-- Error Flash -->
    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/60 p-4 text-red-800 dark:text-red-400 text-xs font-semibold rounded-none">
            <p class="font-bold mb-2">Terjadi kesalahan input:</p>
            <ul class="list-disc list-inside space-y-1 font-mono">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm p-6">
        <form action="{{ route('admin.prestasi.store') }}" method="POST" enctype="multipart/form-data" id="prestasiForm" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Judul Prestasi -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Judul Prestasi <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Contoh: Juara 1 Olimpiade Fisika"
                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                    </div>

                    <!-- Peraih -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Peraih (Siswa / Tim) <span class="text-rose-500">*</span></label>
                        <input type="text" name="peraih" value="{{ old('peraih') }}" required placeholder="Contoh: Ahmad Fauzan atau Tim Basket Putra"
                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                    </div>

                    <!-- Juara & Penyelenggara -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Juara <span class="text-slate-400">(Opsional)</span></label>
                            <input type="text" name="juara" value="{{ old('juara') }}" placeholder="Contoh: Juara 1, Emas"
                                class="w-full px-3 py-2.5 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                        </div>
                        <div>
                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Penyelenggara</label>
                            <input type="text" name="penyelenggara" value="{{ old('penyelenggara') }}" placeholder="Contoh: Univ Diponegoro"
                                class="w-full px-3 py-2.5 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                        </div>
                    </div>

                    <!-- Klasifikasi: Tingkat & Jenis -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Tingkat <span class="text-rose-500">*</span></label>
                            <select name="tingkat" required
                                class="w-full px-3 py-2.5 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                                <option value="sekolah" {{ old('tingkat') === 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                                <option value="kabupaten" {{ old('tingkat') === 'kabupaten' ? 'selected' : '' }}>Kabupaten/Kota</option>
                                <option value="provinsi" {{ old('tingkat') === 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                                <option value="nasional" {{ old('tingkat') === 'nasional' ? 'selected' : '' }}>Nasional</option>
                                <option value="internasional" {{ old('tingkat') === 'internasional' ? 'selected' : '' }}>Internasional</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Jenis <span class="text-rose-500">*</span></label>
                            <select name="jenis" required
                                class="w-full px-3 py-2.5 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                                <option value="akademik" {{ old('jenis') === 'akademik' ? 'selected' : '' }}>Akademik</option>
                                <option value="non_akademik" {{ old('jenis') === 'non_akademik' ? 'selected' : '' }}>Non-Akademik</option>
                            </select>
                        </div>
                    </div>

                    <!-- Waktu: Tahun & Tanggal -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Tahun <span class="text-rose-500">*</span></label>
                            <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" required min="2000" max="2100"
                                class="w-full px-3 py-2.5 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                        </div>
                        <div>
                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Tanggal Prestasi</label>
                            <input type="date" name="tanggal_prestasi" value="{{ old('tanggal_prestasi') }}"
                                class="w-full px-3 py-2.5 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Upload Foto (Drag & Drop + Preview) -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Foto Dokumentasi</label>
                        <input type="hidden" name="temp_foto" id="temp_foto" value="{{ old('temp_foto') }}">
                        <input type="hidden" name="temp_foto_url" id="temp_foto_url" value="{{ old('temp_foto_url') }}">
                        <input type="file" id="foto-file-input" name="foto" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" />
                        
                        <div id="dropzone" class="relative border-2 border-dashed border-slate-200 dark:border-zinc-700 hover:border-[#4f45b2] dark:hover:border-[#4f45b2] bg-slate-50/50 dark:bg-zinc-800/30 p-6 flex flex-col items-center justify-center text-center cursor-pointer transition-all duration-300 rounded-none min-h-[170px]">
                            <!-- Prompt -->
                            <div id="dropzone-prompt" class="space-y-2">
                                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mx-auto text-slate-500 dark:text-zinc-400 transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-xs text-slate-700 dark:text-zinc-300">Tarik gambar ke sini, atau <span class="text-[#4f45b2] underline font-bold">klik telusuri</span></p>
                                <p class="text-[9px] text-slate-400 dark:text-zinc-500">JPG, JPEG, PNG, WEBP. Maks 2MB.</p>
                            </div>

                            <!-- Loading -->
                            <div id="dropzone-loading" class="hidden space-y-2 w-full max-w-[150px]">
                                <div class="flex justify-between text-[9px] font-mono text-slate-500">
                                    <span>Mengunggah...</span>
                                    <span id="upload-progress-text">0%</span>
                                </div>
                                <div class="w-full bg-slate-200 dark:bg-zinc-700 h-1 overflow-hidden">
                                    <div id="upload-progress-bar" class="bg-[#4f45b2] h-1 transition-all duration-100" style="width: 0%"></div>
                                </div>
                            </div>

                            <!-- Preview -->
                            <div id="dropzone-preview" class="hidden space-y-2 w-full">
                                <div class="relative max-w-[160px] mx-auto border border-slate-200 dark:border-zinc-800 shadow shadow-slate-100 dark:shadow-none overflow-hidden bg-white dark:bg-zinc-900 group">
                                    <img id="preview-image" src="" alt="Pratinjau Foto" class="w-full max-h-[110px] object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-200">
                                        <button type="button" id="btn-remove-foto" class="p-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-full transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-[9px] font-mono text-slate-500 truncate px-2" id="preview-filename"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi / Keterangan -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Deskripsi / Keterangan Tambahan</label>
                        <textarea name="deskripsi" rows="4" placeholder="Masukkan keterangan lebih detail mengenai prestasi yang diraih..."
                            class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] resize-y">{{ old('deskripsi') }}</textarea>
                    </div>

                    <!-- Tampilkan Utama (Featured) Toggle -->
                    <div class="flex items-start gap-3 pt-2">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') == '1' ? 'checked' : '' }}
                                class="w-4 h-4 text-[#4f45b2] border-slate-350 focus:ring-[#4f45b2]" />
                        </div>
                        <div class="text-xs">
                            <label for="is_featured" class="font-bold text-slate-700 dark:text-zinc-300">Tampilkan Utama (Featured)</label>
                            <p class="text-[10px] text-slate-450 dark:text-zinc-500">Jika dicentang, prestasi akan ditampilkan sebagai sorotan utama di halaman depan / landing page.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 dark:border-zinc-800">
                <a href="{{ route('admin.prestasi.index') }}" class="py-2.5 px-5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all font-mono">
                    BATAL
                </a>
                <button type="submit" class="py-2.5 px-5 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider font-mono">
                    SIMPAN DATA
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Dropzone upload logic
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('foto-file-input');
        const tempFotoInput = document.getElementById('temp_foto');
        const tempFotoUrlInput = document.getElementById('temp_foto_url');
        
        const promptState = document.getElementById('dropzone-prompt');
        const loadingState = document.getElementById('dropzone-loading');
        const previewState = document.getElementById('dropzone-preview');
        const previewImage = document.getElementById('preview-image');
        const previewFilename = document.getElementById('preview-filename');
        const btnRemove = document.getElementById('btn-remove-foto');
        
        const progressBar = document.getElementById('upload-progress-bar');
        const progressText = document.getElementById('upload-progress-text');

        // Check if there is an existing uploaded temp photo
        if (tempFotoInput.value && tempFotoUrlInput.value) {
            showPreview(tempFotoUrlInput.value, tempFotoInput.value);
        }

        // Trigger file browser on click
        dropzone.addEventListener('click', function(e) {
            if (!e.target.closest('#btn-remove-foto')) {
                fileInput.click();
            }
        });

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFileUpload(this.files[0]);
            }
        });

        // Drag-and-drop
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-[#4f45b2]', 'bg-[#4f45b2]/5');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-[#4f45b2]', 'bg-[#4f45b2]/5');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                handleFileUpload(files[0]);
            }
        });

        function handleFileUpload(file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format berkas tidak valid. Pilih gambar JPG, JPEG, PNG, atau WEBP.');
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran gambar terlalu besar. Maksimal 2MB.');
                return;
            }

            showLoading();

            const formData = new FormData();
            formData.append('foto', file);
            
            const csrfToken = document.querySelector('input[name="_token"]').value;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("admin.prestasi.upload-temp") }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressText.textContent = percentComplete + '%';
                }
            });

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    tempFotoInput.value = response.path;
                    tempFotoUrlInput.value = response.url;
                    showPreview(response.url, file.name);
                } else {
                    alert('Gagal mengunggah gambar ke server.');
                    resetDropzone();
                }
            };

            xhr.onerror = function() {
                alert('Terjadi kesalahan jaringan.');
                resetDropzone();
            };

            xhr.send(formData);
        }

        function showLoading() {
            promptState.classList.add('hidden');
            previewState.classList.add('hidden');
            loadingState.classList.remove('hidden');
            progressBar.style.width = '0%';
            progressText.textContent = '0%';
        }

        function showPreview(url, filename) {
            loadingState.classList.add('hidden');
            promptState.classList.add('hidden');
            previewState.classList.remove('hidden');
            previewImage.src = url;
            previewFilename.textContent = filename;
        }

        function resetDropzone() {
            loadingState.classList.add('hidden');
            previewState.classList.add('hidden');
            promptState.classList.remove('hidden');
            fileInput.value = '';
        }

        btnRemove.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            tempFotoInput.value = '';
            tempFotoUrlInput.value = '';
            resetDropzone();
        });
    });
</script>
@endsection
