@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Tambah Popup Alert';
        }
    });
</script>

<div class="max-w-6xl space-y-6">
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Tambah Popup Alert Baru</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Buat popup modal dialog dengan slider gambar untuk menampilkan info penting langsung di tengah layar.</p>
        </div>
        <a href="{{ route('admin.announcements.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all text-center">
            Kembali ke Daftar
        </a>
    </div>

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
        <form action="{{ route('admin.announcements.alerts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- 2 Columns Layout: Left = Media, Right = Content details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- LEFT COLUMN: Multiple Images Drag and Drop Zone -->
                <div class="flex flex-col space-y-4">
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                        Berkas Gambar Banner Popup
                        <span class="text-[10px] font-normal lowercase italic text-slate-400 dark:text-zinc-500">(Bisa pilih beberapa gambar sekaligus untuk slide/carousel)</span>
                    </label>

                    <div id="dropzone" 
                         class="relative border-2 border-dashed border-slate-300 dark:border-zinc-700 hover:border-indigo-400 dark:hover:border-zinc-500 bg-slate-50 dark:bg-zinc-950 p-6 flex flex-col items-center justify-center min-h-[200px] text-center cursor-pointer transition-all group">
                        
                        <input type="file" name="images[]" id="fileInput" class="hidden" accept="image/*" multiple />
                        
                        <div class="space-y-3 py-4">
                            <div class="p-3 bg-indigo-50 dark:bg-zinc-900 rounded-full inline-block group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-700 dark:text-zinc-300">Tarik & Lepas beberapa gambar di sini</p>
                                <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">atau klik untuk memilih berkas sekaligus</p>
                            </div>
                            <p class="text-[9px] font-mono text-slate-400 dark:text-zinc-500 uppercase">PNG, JPG, JPEG, WEBP (Maks. 2MB per berkas)</p>
                        </div>
                    </div>

                    <!-- Images Preview Gallery Grid (Horizontal Scroll) -->
                    <div class="space-y-2">
                        <h4 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Pratinjau Gambar & Gambar Utama:</h4>
                        
                        <!-- Hidden inputs for main image original name -->
                        <input type="hidden" name="main_image_name" id="mainImageName" value="" />
                        
                        <div id="gallery-container" class="flex flex-row flex-nowrap overflow-x-auto gap-4 p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 min-h-[160px] items-center scrollbar-thin">
                            <span class="w-full text-center text-xs text-slate-400 dark:text-zinc-650 py-8 italic">Belum ada gambar yang dipilih</span>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Title, Content, CTA Link & Text Stacked Vertically -->
                <div class="flex flex-col space-y-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Judul Popup <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Pengumuman Pembukaan Gelombang 2"
                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                    </div>

                    <!-- Content -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Isi Pesan / Informasi Detil</label>
                        <textarea name="content" rows="4" placeholder="Masukkan teks pengumuman di sini..."
                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] resize-y">{{ old('content') }}</textarea>
                    </div>

                    <!-- Action Link -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Link Tautan Tombol (Action URL)</label>
                        <input type="url" name="action_url" value="{{ old('action_url') }}" placeholder="https://example.com/halaman-tujuan"
                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                    </div>

                    <!-- Action Text -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Teks Tombol Aksi</label>
                        <input type="text" name="action_text" value="{{ old('action_text') }}" placeholder="Contoh: Daftar Sekarang / Hubungi Kami"
                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                    </div>
                </div>
            </div>

            <!-- LOWER GRID SECTION: Sizes, Frequencies, Target Pages, Start/End Dates & Active -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-slate-200 dark:border-zinc-800">
                <!-- Sizes -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Ukuran Modal Popup</label>
                    <select name="popup_size" class="w-full py-2.5 px-3 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                        <option value="sm">Kecil (Small)</option>
                        <option value="md" selected>Sedang (Medium)</option>
                        <option value="lg">Lebar (Large)</option>
                        <option value="xl">Sangat Lebar (X-Large)</option>
                    </select>
                </div>

                <!-- Frequency -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Frekuensi Tampil</label>
                    <select name="display_frequency" class="w-full py-2.5 px-3 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                        <option value="once_per_session" selected>Sekali Per Sesi Browser (Rekomendasi)</option>
                        <option value="every_load">Setiap Membuka Halaman (Always)</option>
                    </select>
                </div>

                <!-- Target Page -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Ditampilkan Pada Halaman</label>
                    <select name="target_page" class="w-full py-2.5 px-3 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                        <option value="all_pages" selected>Semua Halaman Publik</option>
                        <option value="frontend.home">Beranda Utama (Home)</option>
                        <option value="frontend.ppdb.index">Halaman Utama PPDB</option>
                        <option value="frontend.article.index">Halaman Artikel</option>
                        <option value="frontend.jurusan">Halaman Jurusan</option>
                        <option value="frontend.kurikulum">Halaman Kurikulum</option>
                        <option value="frontend.ekstrakurikuler">Halaman Ekstrakurikuler</option>
                        <option value="frontend.prestasi">Halaman Prestasi</option>
                        <option value="frontend.galeri">Halaman Galeri</option>
                        <option value="frontend.profile">Halaman Profil</option>
                        <option value="frontend.contact">Halaman Kontak</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Mulai Tayang (Start Date) <span class="text-[10px] font-normal lowercase italic text-slate-400">(Opsional)</span></label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                        class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none" />
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Selesai Tayang (End Date) <span class="text-[10px] font-normal lowercase italic text-slate-400">(Opsional)</span></label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                        class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none" />
                </div>

                <!-- Active switch -->
                <div class="flex items-center gap-3 pt-6">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                        class="w-4 h-4 text-[#4f45b2] border-slate-300 focus:ring-[#4f45b2] rounded-none" />
                    <label for="is_active" class="text-sm font-bold text-slate-700 dark:text-zinc-300 select-none">Aktifkan popup modal ini langsung</label>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 dark:border-zinc-800">
                <a href="{{ route('admin.announcements.index') }}" class="py-2 px-5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all">
                    Batal
                </a>
                <button type="submit" class="py-2 px-5 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider">
                    Simpan Popup Alert
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const galleryContainer = document.getElementById('gallery-container');
        const mainImageNameInput = document.getElementById('mainImageName');

        let selectedFiles = [];
        let mainImageNameValue = '';

        // Click triggers file input
        dropzone.addEventListener('click', function(e) {
            fileInput.click();
        });

        // Drag & drop triggers
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-indigo-950/10');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-indigo-950/10');
            }, false);
        });

        dropzone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                addFiles(files);
            }
        });

        fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                addFiles(fileInput.files);
            }
        });

        function addFiles(filesList) {
            for (let i = 0; i < filesList.length; i++) {
                const file = filesList[i];
                if (file.type.startsWith('image/')) {
                    const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (!exists) {
                        selectedFiles.push(file);
                        if (!mainImageNameValue) {
                            mainImageNameValue = file.name;
                        }
                    }
                }
            }
            syncInput();
            renderGallery();
        }

        window.removeSelectedFile = function(index, name) {
            selectedFiles.splice(index, 1);
            if (mainImageNameValue === name) {
                mainImageNameValue = selectedFiles.length > 0 ? selectedFiles[0].name : '';
            }
            syncInput();
            renderGallery();
        };

        window.setMainImage = function(name) {
            mainImageNameValue = name;
            syncInput();
            renderGallery();
        };

        function syncInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
            mainImageNameInput.value = mainImageNameValue;
        }

        function renderGallery() {
            galleryContainer.innerHTML = '';
            
            if (selectedFiles.length === 0) {
                galleryContainer.innerHTML = '<span class="w-full text-center text-xs text-slate-400 dark:text-zinc-650 py-8 italic">Belum ada gambar yang dipilih</span>';
                return;
            }

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const isMain = file.name === mainImageNameValue;
                    const card = document.createElement('div');
                    card.className = "w-32 h-32 flex-shrink-0 relative group border border-slate-200 dark:border-zinc-800 overflow-hidden bg-white dark:bg-zinc-900";
                    card.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover" />
                        
                        <div class="absolute top-1 left-1 z-10">
                            ${isMain 
                                ? '<span class="bg-emerald-600 text-white font-mono text-[8px] font-bold px-1.5 py-0.5 shadow-sm">UTAMA ⭐</span>' 
                                : '<button type="button" onclick="setMainImage(\'' + file.name.replace(/'/g, "\\'") + '\')" class="bg-black/75 hover:bg-emerald-600 text-white font-mono text-[8px] px-1.5 py-0.5 shadow-sm transition-colors">Utama</button>'
                            }
                        </div>

                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button type="button" class="p-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-none shadow-sm text-xs" onclick="removeSelectedFile(${index}, '${file.name.replace(/'/g, "\\'")}')">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    `;
                    galleryContainer.appendChild(card);
                };
                reader.readAsDataURL(file);
            });
        }
    });
</script>
@endsection
