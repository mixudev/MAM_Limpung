@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Tambah Iklan Banner';
        }
    });
</script>

<div class="max-w-6xl space-y-6">
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Tambah Iklan Banner Baru</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Unggah berkas gambar promosi visual untuk ditampilkan sebagai banner iklan/seksi promosi.</p>
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
        <form action="{{ route('admin.announcements.ads.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- 2 Columns Layout for Media and Text Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- LEFT COLUMN: Drag and Drop Upload Image (Vanilla JS) -->
                <div class="flex flex-col">
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Berkas Gambar Banner Iklan <span class="text-rose-500">*</span></label>
                    
                    <div id="dropzone" 
                         class="relative border-2 border-dashed border-slate-300 dark:border-zinc-700 hover:border-indigo-400 dark:hover:border-zinc-500 bg-slate-50 dark:bg-zinc-950 p-6 flex flex-col items-center justify-center min-h-[300px] text-center cursor-pointer transition-all group flex-1">
                        
                        <input type="file" name="image" id="fileInput" class="hidden" accept="image/*" required />
                        
                        <!-- Empty State -->
                        <div id="empty-state" class="space-y-4 py-8">
                            <div class="p-4 bg-indigo-50 dark:bg-zinc-900 rounded-full inline-block group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-700 dark:text-zinc-300">Tarik & Lepas gambar di sini</p>
                                <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">atau klik untuk memilih berkas</p>
                            </div>
                            <p class="text-[10px] font-mono text-slate-400 dark:text-zinc-500 uppercase">PNG, JPG, JPEG, WEBP, GIF (Maks. 2MB)</p>
                        </div>

                        <!-- Preview State -->
                        <div id="preview-state" class="w-full h-full flex flex-col items-center justify-center relative group/preview hidden">
                            <img id="image-preview" src="" class="max-w-full max-h-[280px] object-contain border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900" />
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/preview:opacity-100 transition-opacity flex items-center justify-center">
                                <button type="button" id="btn-remove" class="py-2 px-4 bg-rose-600 hover:bg-rose-700 text-white font-mono font-bold text-xs uppercase tracking-wider">
                                    Ganti Gambar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Input Title & Description & CTA Links (Stacked Vertically) -->
                <div class="flex flex-col space-y-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Nama / Judul Promosi <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Banner Promosi PPDB 2026"
                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Deskripsi Promosi</label>
                        <textarea name="description" rows="4" placeholder="Masukkan deskripsi singkat penjelas tentang promosi atau iklan ini..."
                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] resize-y">{{ old('description') }}</textarea>
                    </div>

                    <!-- Action Link -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Tautan URL Klik (Action URL)</label>
                        <input type="url" name="action_url" value="{{ old('action_url') }}" placeholder="https://example.com/halaman-ppdb"
                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                    </div>

                    <!-- Action Text -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Teks Link Tautan</label>
                        <input type="text" name="action_text" value="{{ old('action_text') }}" placeholder="Contoh: Info Lengkap / Daftar"
                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                    </div>
                </div>
            </div>

            <!-- LOWER GRID SECTION: Durations & Switch Active -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-200 dark:border-zinc-800">
                <!-- Durations -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Mulai Tayang (Start Date) <span class="text-[10px] font-normal lowercase italic text-slate-400">(Opsional - Kosongkan untuk kapan saja)</span></label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                        class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none" />
                </div>

                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Selesai Tayang (End Date) <span class="text-[10px] font-normal lowercase italic text-slate-400">(Opsional - Kosongkan untuk selamanya)</span></label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                        class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none" />
                </div>

                <!-- Status -->
                <div class="md:col-span-2 flex items-center gap-3 pt-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                        class="w-4 h-4 text-[#4f45b2] border-slate-300 focus:ring-[#4f45b2] rounded-none" />
                    <label for="is_active" class="text-sm font-bold text-slate-700 dark:text-zinc-300 select-none">Aktifkan banner promosi ini langsung</label>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 dark:border-zinc-800">
                <a href="{{ route('admin.announcements.index') }}" class="py-2 px-5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all">
                    Batal
                </a>
                <button type="submit" class="py-2 px-5 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider">
                    Simpan Iklan Banner
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const emptyState = document.getElementById('empty-state');
        const previewState = document.getElementById('preview-state');
        const imagePreview = document.getElementById('image-preview');
        const btnRemove = document.getElementById('btn-remove');

        // Click triggers file input
        dropzone.addEventListener('click', function(e) {
            if (e.target !== btnRemove && !btnRemove.contains(e.target)) {
                fileInput.click();
            }
        });

        // Drag/Drop handling
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
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    fileInput.files = files;
                    handleFile(file);
                }
            }
        });

        fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                handleFile(fileInput.files[0]);
            }
        });

        btnRemove.addEventListener('click', function(e) {
            e.stopPropagation();
            resetUploader();
        });

        function handleFile(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                emptyState.classList.add('hidden');
                previewState.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function resetUploader() {
            fileInput.value = '';
            imagePreview.src = '';
            previewState.classList.add('hidden');
            emptyState.classList.remove('hidden');
        }
    });
</script>
@endsection
