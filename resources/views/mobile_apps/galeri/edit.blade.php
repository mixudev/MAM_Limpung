@extends('mobile_apps.layouts.apps')

@section('content')
    <div class="px-5 pt-4">
        <!-- Header & Back Button -->
        <div class="flex items-center gap-3 mb-5">
            <a href="{{ route('apps.galeri.show', $galeri) }}" class="w-8 h-8 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-600 shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-sora font-bold text-slate-800 text-base">Ubah Galeri Sekolah</h2>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-slate-100/80 shadow-xs rounded-2xl p-5 mb-6" x-data="galleryUpload()">
            <form id="gallery-edit-form" action="{{ route('apps.galeri.update', $galeri) }}" method="POST" enctype="multipart/form-data" @submit="handleSubmit($event)">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Judul Kegiatan</label>
                        <input type="text" name="judul" value="{{ old('judul', $galeri->judul) }}" required placeholder="Contoh: Kemah Bakti Pramuka 2026"
                               class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all font-semibold">
                        @error('judul')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Deskripsi Kegiatan</label>
                        <textarea name="deskripsi" rows="3" required placeholder="Tulis rincian singkat kegiatan..."
                                  class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all font-semibold">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Kategori</label>
                            <select name="kategori" required
                                    class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all font-semibold">
                                <option value="Kegiatan" {{ old('kategori', $galeri->kategori) == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                <option value="Prestasi" {{ old('kategori', $galeri->kategori) == 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
                                <option value="Kelas" {{ old('kategori', $galeri->kategori) == 'Kelas' ? 'selected' : '' }}>Kelas</option>
                                <option value="Pramuka" {{ old('kategori', $galeri->kategori) == 'Pramuka' ? 'selected' : '' }}>Pramuka</option>
                                <option value="Umum" {{ old('kategori', $galeri->kategori) == 'Umum' ? 'selected' : '' }}>Umum</option>
                            </select>
                            @error('kategori')
                                <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Tahun</label>
                            <input type="number" name="tahun" value="{{ old('tahun', $galeri->tahun) }}" required min="2020" max="{{ date('Y') + 1 }}"
                                   class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all font-semibold">
                            @error('tahun')
                                <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Existing Photos Info -->
                    @if($galeri->photos && $galeri->photos->count() > 0)
                        <div x-show="selectedFiles.length === 0" class="space-y-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Foto Saat Ini</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($galeri->photos as $photo)
                                    <div class="aspect-square w-full rounded-xl overflow-hidden bg-slate-50 border border-slate-100 shadow-xs relative">
                                        <img src="{{ Storage::url($photo->file_path) }}" alt="Photo" class="w-full h-full object-cover">
                                        @if($photo->is_cover)
                                            <span class="absolute bottom-1 left-1 text-[6px] bg-primary-600 text-white font-bold px-1 py-0.5 rounded-sm uppercase tracking-wider shadow-xs">Cover</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Interactive Photo Upload Component -->
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Ganti Foto Kegiatan</label>
                            <span class="text-[9px] text-slate-400 font-semibold">(Kosongkan jika tidak diganti)</span>
                        </div>
                        
                        <div class="relative group"
                             @dragover.prevent="dragOver = true"
                             @dragleave.prevent="dragOver = false"
                             @drop.prevent="handleDrop($event)">
                            
                            <input type="file" name="photos[]" multiple accept="image/*" id="photos-input" x-ref="fileInput"
                                   @change="handleSelect($event)"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <div class="border-2 border-dashed rounded-2xl p-6 text-center transition-all duration-300"
                                 :class="dragOver ? 'border-primary-500 bg-primary-50/30' : 'border-slate-200 bg-slate-50 group-hover:border-primary-400'">
                                
                                <svg class="w-8 h-8 text-slate-400 mx-auto mb-2 transition-transform duration-300"
                                     :class="dragOver ? 'scale-110 text-primary-500' : 'group-hover:text-primary-500'" 
                                     fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                
                                <p class="text-xs text-slate-500 font-bold" x-text="dragOver ? 'Lepaskan foto di sini...' : 'Pilih foto kegiatan baru'"></p>
                                <p class="text-[10px] text-slate-400 mt-1">Mengunggah foto baru akan mengganti seluruh foto lama.</p>
                            </div>
                        </div>
                        @error('photos')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror

                        <!-- Previews Grid & Progress Animation -->
                        <div class="mt-4 space-y-3" x-show="selectedFiles.length > 0" style="display: none;">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Foto Baru Terpilih (<span x-text="selectedFiles.length"></span>)</p>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <template x-for="file in selectedFiles" :key="file.id">
                                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-2 flex flex-col justify-between relative overflow-hidden group shadow-xs">
                                        <!-- Image Preview container -->
                                        <div class="aspect-square w-full rounded-lg overflow-hidden bg-slate-200 relative">
                                            <template x-if="file.status === 'ready'">
                                                <img :src="file.dataUrl" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                            </template>
                                            
                                            <!-- Shimmer/Loading state when progress < 100 -->
                                            <template x-if="file.status === 'loading'">
                                                <div class="w-full h-full bg-slate-200 flex flex-col items-center justify-center p-3">
                                                    <!-- Loading spinner -->
                                                    <svg class="animate-spin h-5 w-5 text-primary-500 mb-1" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                    </svg>
                                                    <span class="text-[8px] font-bold text-slate-500">Membaca file...</span>
                                                </div>
                                            </template>

                                            <!-- Delete Badge Button -->
                                            <button type="button" @click="removeFile(file.id)"
                                                    class="absolute top-1.5 right-1.5 w-6 h-6 bg-rose-500 hover:bg-rose-600 text-white rounded-full flex items-center justify-center shadow-md cursor-pointer transition-transform active:scale-90">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Progress Bar (Upload Animation) -->
                                        <div class="mt-2" x-show="file.status === 'loading'">
                                            <div class="w-full bg-slate-200 rounded-full h-1 overflow-hidden">
                                                <div class="bg-primary-500 h-1 transition-all duration-100 ease-out" :style="'width: ' + file.progress + '%'"></div>
                                            </div>
                                            <div class="flex justify-between text-[7px] text-slate-400 mt-0.5 font-bold">
                                                <span x-text="formatSize(file.size)"></span>
                                                <span x-text="file.progress + '%'"></span>
                                            </div>
                                        </div>

                                        <div class="mt-2 px-1" x-show="file.status === 'ready'">
                                            <p class="text-[9px] text-slate-700 font-bold truncate" :title="file.name" x-text="file.name"></p>
                                            <p class="text-[8px] text-slate-400 font-semibold" x-text="formatSize(file.size)"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="gallery-submit-btn" :disabled="submitting"
                            class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold shadow-md active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer mt-6 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" x-show="!submitting">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" x-show="submitting" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span x-text="submitting ? 'Mengirim & Mengunggah...' : 'Simpan Perubahan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function galleryUpload() {
            return {
                selectedFiles: [],
                dragOver: false,
                fileId: 0,
                submitting: false,

                handleDrop(e) {
                    this.dragOver = false;
                    const files = Array.from(e.dataTransfer.files);
                    this.processFiles(files);
                },

                handleSelect(e) {
                    const files = Array.from(e.target.files);
                    this.processFiles(files);
                },

                processFiles(files) {
                    files.forEach(file => {
                        // Max 2MB limit
                        if (file.size > 2 * 1024 * 1024) {
                            if (window.MobilePopup) {
                                window.MobilePopup.error({
                                    title: 'Foto Terlalu Besar',
                                    description: `Ukuran foto "${file.name}" melebihi 2MB. Silakan pilih foto yang lebih kecil.`,
                                    confirmText: 'Tutup'
                                });
                            } else {
                                alert(`Ukuran foto "${file.name}" melebihi 2MB.`);
                            }
                            return;
                        }

                        // Validate file type is image
                        if (!file.type.match('image.*')) {
                            alert(`File "${file.name}" harus berupa gambar.`);
                            return;
                        }

                        const id = this.fileId++;
                        const fileObj = {
                            id: id,
                            name: file.name,
                            size: file.size,
                            file: file,
                            dataUrl: '',
                            progress: 0,
                            status: 'loading'
                        };

                        this.selectedFiles.push(fileObj);

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const found = this.selectedFiles.find(f => f.id === id);
                            if (found) {
                                found.dataUrl = e.target.result;
                                found.progress = 100;
                                found.status = 'ready';
                            }
                            // Force Alpine reactivity
                            this.selectedFiles = [...this.selectedFiles];
                            this.syncInput();
                        };
                        reader.readAsDataURL(file);
                    });
                },

                removeFile(id) {
                    this.selectedFiles = this.selectedFiles.filter(f => f.id !== id);
                    this.syncInput();
                },

                syncInput() {
                    const dt = new DataTransfer();
                    this.selectedFiles.forEach(f => {
                        if (f.file) {
                            dt.items.add(f.file);
                        }
                    });
                    const inputEl = document.getElementById('photos-input');
                    if (inputEl) {
                        inputEl.files = dt.files;
                    }
                },

                formatSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                },

                handleSubmit(e) {
                    this.submitting = true;
                    if (window.showGlobalLoader) {
                        window.showGlobalLoader('Menyimpan Perubahan...', 'Sedang memproses dan mengunggah berkas baru Anda');
                    }
                }
            }
        }
    </script>
@endsection
