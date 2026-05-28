@extends('dashboard.layouts.main')

@section('content')
<!-- Load Alpine.js CDN for dynamic client-side interactions -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Tambah Galeri Foto';
        }
    });
</script>

<div class="max-w-4xl space-y-6" x-data="galeriForm()">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Buat Album Galeri Baru</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Unggah dokumentasi foto kegiatan sekolah atau bagikan tautan gambar eksternal.</p>
        </div>
        <a href="{{ route('admin.galeri.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all text-center font-mono">
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
        <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- TOP SECTION: Upload & Media Inputs (Grid) -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <!-- Left Column (3/5): Drag & Drop Zone -->
                <div class="md:col-span-3 space-y-2">
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-450 dark:text-zinc-400">Unggah Berkas Gambar</label>
                    <div class="flex items-center justify-center w-full">
                        <label 
                            @dragover.prevent="dragOver = true"
                            @dragleave.prevent="dragOver = false"
                            @drop.prevent="dragOver = false; handleDrop($event)"
                            :class="dragOver ? 'border-[#4f45b2] bg-[#4f45b2]/5 dark:bg-indigo-950/20' : 'border-slate-200 dark:border-zinc-750 hover:border-[#4f45b2] bg-slate-50/50 dark:bg-zinc-800/20'"
                            class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed cursor-pointer transition-all duration-300 select-none rounded-none"
                        >
                            <div class="flex flex-col items-center justify-center pt-4 pb-5 text-center px-4">
                                <svg class="w-7 h-7 mb-2 text-slate-400 dark:text-zinc-500 transition-colors" :class="dragOver ? 'text-[#4f45b2]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-xs text-slate-600 dark:text-zinc-400"><span class="text-[#4f45b2] font-semibold underline">Pilih beberapa foto</span> atau seret foto ke sini</p>
                                <p class="text-[9px] text-slate-400 dark:text-zinc-550 mt-1">JPG, PNG, JPEG, WEBP. Maks 2MB per foto.</p>
                            </div>
                            <input 
                                type="file" 
                                id="photos-file-input" 
                                name="photos[]" 
                                multiple 
                                accept="image/*" 
                                class="hidden" 
                                @change="handleFileChange($event)" 
                            />
                        </label>
                    </div>
                </div>

                <!-- Right Column (2/5): External Link Inputs -->
                <div class="md:col-span-2 space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-450 dark:text-zinc-400">Tautan Gambar Eksternal (URL)</label>
                        <button type="button" @click="addLink()" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-bold text-[9px] uppercase tracking-wider font-mono">
                            + Tautan
                        </button>
                    </div>
                    
                    <div class="space-y-2 max-h-36 overflow-y-auto pr-1">
                        <template x-for="(link, index) in links" :key="index">
                            <div class="flex gap-2 items-center">
                                <input type="url" name="links[]" x-model="link.url" placeholder="https://example.com/gambar.jpg"
                                    class="flex-1 px-3 py-1.5 text-xs bg-white dark:bg-zinc-850 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]" />
                                <button type="button" @click="removeLink(index)" class="p-2 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 rounded-none">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <div x-show="links.length === 0" class="text-[10px] text-slate-400 dark:text-zinc-500 italic">Belum ada tautan gambar eksternal yang ditambahkan.</div>
                    </div>
                </div>
            </div>

            <!-- MIDDLE SECTION: Previews Grid -->
            <div class="border-t border-slate-100 dark:border-zinc-800 pt-6 space-y-3">
                <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Pratinjau Media & Penentuan Sampul Utama (Klik Bintang)</h3>
                
                <!-- Hidden fields for cover validation -->
                <input type="hidden" name="cover_type" :value="coverType">
                <input type="hidden" name="cover_index" :value="coverIndex">

                <!-- Preview Grid (Small Squares) -->
                <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                    <!-- File Previews -->
                    <template x-for="(file, index) in files" :key="'file-'+index">
                        <div class="relative aspect-square border border-slate-200 dark:border-zinc-800 p-1 bg-slate-50 dark:bg-zinc-950 flex flex-col justify-between group overflow-hidden">
                            <div class="w-full h-full relative overflow-hidden bg-slate-100 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800">
                                <img :src="file.src" class="absolute inset-0 w-full h-full object-cover">
                                
                                <!-- Cover Selector Button (Star Icon) -->
                                <button type="button" @click="coverType = 'file'; coverIndex = index"
                                    class="absolute top-1 left-1 p-1 bg-black/50 hover:bg-black/80 rounded-none transition-all duration-200 cursor-pointer"
                                    :class="coverType === 'file' && coverIndex === index ? 'opacity-100 scale-110 border border-amber-400/50' : 'opacity-0 group-hover:opacity-100 scale-100'"
                                    title="Jadikan Sampul Utama">
                                    <i class="fa-solid fa-star text-[10px]" :class="coverType === 'file' && coverIndex === index ? 'text-amber-400' : 'text-white/85'"></i>
                                </button>

                                <!-- Remove Photo Action -->
                                <button type="button" @click="removeFile(index)" 
                                    class="absolute top-1 right-1 p-1 bg-black/50 hover:bg-rose-600/90 text-white opacity-0 group-hover:opacity-100 transition-opacity rounded-none cursor-pointer"
                                    title="Hapus Foto">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                
                                <!-- Cover Active Badge -->
                                <div x-show="coverType === 'file' && coverIndex === index" 
                                     class="absolute bottom-0 left-0 right-0 bg-amber-500 text-zinc-950 text-[7px] font-bold py-0.5 text-center uppercase tracking-wider select-none font-mono">
                                    SAMPUL
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Link Previews -->
                    <template x-for="(link, index) in links" :key="'link-'+index">
                        <div x-show="link.url && (link.url.startsWith('http') || link.url.startsWith('https'))" 
                             class="relative aspect-square border border-slate-200 dark:border-zinc-800 p-1 bg-slate-50 dark:bg-zinc-950 flex flex-col justify-between group overflow-hidden">
                            <div class="w-full h-full relative overflow-hidden bg-slate-100 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800">
                                <img :src="link.url" class="absolute inset-0 w-full h-full object-cover" 
                                     onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\'><rect width=\'100%\' height=\'100%\' fill=\'%2318181b\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%23ef4444\' font-size=\'7\' font-family=\'sans-serif\'>Gagal Memuat</text></svg>'">
                                
                                <!-- Cover Selector Button (Star Icon) -->
                                <button type="button" @click="coverType = 'link'; coverIndex = index"
                                    class="absolute top-1 left-1 p-1 bg-black/50 hover:bg-black/80 rounded-none transition-all duration-200 cursor-pointer"
                                    :class="coverType === 'link' && coverIndex === index ? 'opacity-100 scale-110 border border-amber-400/50' : 'opacity-0 group-hover:opacity-100 scale-100'"
                                    title="Jadikan Sampul Utama">
                                    <i class="fa-solid fa-star text-[10px]" :class="coverType === 'link' && coverIndex === index ? 'text-amber-400' : 'text-white/85'"></i>
                                </button>
                                
                                <!-- Cover Active Badge -->
                                <div x-show="coverType === 'link' && coverIndex === index" 
                                     class="absolute bottom-0 left-0 right-0 bg-amber-500 text-zinc-950 text-[7px] font-bold py-0.5 text-center uppercase tracking-wider select-none font-mono">
                                    SAMPUL
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="files.length === 0 && links.filter(l => l.url && (l.url.startsWith('http') || l.url.startsWith('https'))).length === 0" class="text-xs text-slate-400 dark:text-zinc-550 italic p-4 border border-dashed border-slate-200 dark:border-zinc-800 text-center">
                    Harus mengunggah foto atau memasukkan tautan gambar terlebih dahulu untuk melihat pratinjau dan memilih sampul utama.
                </div>
            </div>

            <!-- BOTTOM SECTION: Basic Info Form Fields (Compact) -->
            <div class="border-t border-slate-100 dark:border-zinc-800 pt-6 space-y-4">
                <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Informasi Album Kegiatan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Judul -->
                    <div class="md:col-span-1">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-450 dark:text-zinc-550 mb-2">Judul Album <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="MABIT Ramadhan 1447 H"
                            class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                    </div>

                    <!-- Kategori (Custom Select Dropdown) -->
                    <div class="md:col-span-1">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-450 dark:text-zinc-550 mb-2">Kategori <span class="text-rose-500">*</span></label>
                        <div class="relative" x-data="{ open: false, selected: '{{ old('kategori', 'Belajar') }}' }">
                            <button type="button" @click="open = !open" @click.away="open = false"
                                class="w-full flex items-center justify-between px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] cursor-pointer text-left h-[34px]">
                                <span x-text="selected"></span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 dark:text-zinc-550 transition-transform duration-250" :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <input type="hidden" name="kategori" :value="selected">
                            
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-40 mt-1 w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none shadow-xl py-1.5 focus:outline-none">
                                <template x-for="option in ['Belajar', 'Ekskul', 'Fasilitas', 'Event Seru']">
                                    <button type="button" @click="selected = option; open = false"
                                        class="w-full text-left px-4 py-2 text-xs text-slate-750 dark:text-zinc-350 hover:bg-[#4f45b2] hover:text-white dark:hover:bg-[#4f45b2] transition-colors duration-150 flex items-center justify-between cursor-pointer"
                                        :class="selected === option ? 'bg-slate-50 dark:bg-zinc-700/50 font-bold text-[#4f45b2] dark:text-indigo-400' : ''">
                                        <span x-text="option"></span>
                                        <i x-show="selected === option" class="fa-solid fa-check text-[10px] text-[#4f45b2] dark:text-indigo-400"></i>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Tahun -->
                    <div class="md:col-span-1">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-450 dark:text-zinc-550 mb-2">Tahun <span class="text-rose-500">*</span></label>
                        <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" required min="2000" max="2100"
                            class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-450 dark:text-zinc-550 mb-2">Deskripsi Album Kegiatan</label>
                    <textarea name="deskripsi" rows="3" placeholder="Ceritakan detail kegiatan atau gambar-gambar ini..."
                        class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] resize-y">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 dark:border-zinc-800">
                <a href="{{ route('admin.galeri.index') }}" class="py-2.5 px-5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all font-mono">
                    BATAL
                </a>
                <button type="submit" class="py-2.5 px-5 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider font-mono cursor-pointer">
                    SIMPAN GALERI
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function galeriForm() {
    return {
        // Map old links to objects or initialize with one empty object
        links: {!! json_encode(array_map(fn($val) => ['url' => $val], old('links', []))) !!},
        files: [],
        rawFiles: [],
        dragOver: false,
        coverType: '{{ old('cover_type', 'file') }}',
        coverIndex: {{ (int) old('cover_index', 0) }},
        isSyncingFiles: false,

        init() {
            if (this.links.length === 0) {
                this.links.push({ url: '' });
            }
            this.setDefaultCover();
        },

        addLink() {
            this.links.push({ url: '' });
        },

        removeLink(index) {
            this.links.splice(index, 1);
            if (this.coverType === 'link' && this.coverIndex === index) {
                this.setDefaultCover();
            } else if (this.coverType === 'link' && this.coverIndex > index) {
                this.coverIndex--;
            }
        },

        handleDrop(event) {
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                this.processFiles(files);
            }
        },

        handleFileChange(event) {
            if (this.isSyncingFiles) return;
            const files = event.target.files;
            if (files.length > 0) {
                this.processFiles(files);
            }
        },

        processFiles(fileList) {
            if (this.isSyncingFiles) return;
            this.isSyncingFiles = true;

            const dt = new DataTransfer();
            // Load existing raw files
            this.rawFiles.forEach(f => dt.items.add(f));

            for (let i = 0; i < fileList.length; i++) {
                const file = fileList[i];
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Format berkas tidak valid. Pilih gambar JPG, JPEG, PNG, atau WEBP.');
                    continue;
                }
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran gambar terlalu besar. Maksimal 2MB.');
                    continue;
                }

                // Skip duplicates
                const isDuplicate = this.rawFiles.some(f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified);
                if (isDuplicate) {
                    continue;
                }

                dt.items.add(file);
                this.rawFiles.push(file);

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.files.push({
                        name: file.name,
                        src: e.target.result,
                        index: this.files.length - 1
                    });

                    // Update indexes
                    this.files.forEach((f, idx) => f.index = idx);

                    // Auto-select cover if none set
                    if (this.coverType === '') {
                        this.coverType = 'file';
                        this.coverIndex = 0;
                    }
                };
                reader.readAsDataURL(file);
            }

            const fileInput = document.getElementById('photos-file-input');
            if (fileInput) {
                fileInput.files = dt.files;
            }

            this.isSyncingFiles = false;
        },

        removeFile(index) {
            this.files.splice(index, 1);
            this.rawFiles.splice(index, 1);

            // Re-index remaining previews
            this.files.forEach((f, idx) => f.index = idx);

            const dt = new DataTransfer();
            this.rawFiles.forEach(f => dt.items.add(f));

            const fileInput = document.getElementById('photos-file-input');
            if (fileInput) {
                fileInput.files = dt.files;
            }

            if (this.coverType === 'file' && this.coverIndex === index) {
                this.setDefaultCover();
            } else if (this.coverType === 'file' && this.coverIndex > index) {
                this.coverIndex--;
            }
        },

        setDefaultCover() {
            if (this.files.length > 0) {
                this.coverType = 'file';
                this.coverIndex = 0;
            } else {
                const validLinks = this.links.filter(l => l.url && (l.url.startsWith('http') || l.url.startsWith('https')));
                if (validLinks.length > 0) {
                    this.coverType = 'link';
                    const firstVal = this.links.findIndex(l => l.url && (l.url.startsWith('http') || l.url.startsWith('https')));
                    this.coverIndex = firstVal !== -1 ? firstVal : 0;
                } else {
                    this.coverType = '';
                    this.coverIndex = 0;
                }
            }
        }
    }
}
</script>
@endsection
