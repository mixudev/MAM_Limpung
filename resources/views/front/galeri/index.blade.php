@extends('layouts.app')

@section('content')
<div x-data="gallery()" class="bg-[#fcfcfc] min-h-screen font-sans overflow-x-hidden w-full">
    
    <!-- Hero Section: Casual & Sharp -->
    <div class="bg-blue-900 text-white pt-32 pb-20 px-4 relative overflow-hidden border-b-[8px] border-amber-500">
        <!-- Abstract geometric accents -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 transform rotate-45 translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-amber-500/20 transform -rotate-12 -translate-x-1/4 translate-y-1/4"></div>

        <div class="max-w-4xl mx-auto text-center relative z-20">
            <span class="inline-block px-3 py-1 bg-amber-500 text-blue-900 text-[10px] font-bold uppercase tracking-widest mb-6">
                <i class="fa-solid fa-camera-retro mr-1"></i> Album Kita
            </span>
            <h1 class="text-5xl md:text-7xl font-black mb-4 tracking-tighter uppercase leading-none">Momen<br><span class="text-amber-500">Seru!</span></h1>
            <p class="text-blue-100 text-sm md:text-base max-w-xl mx-auto font-medium mt-6 leading-relaxed">
                Ngapain aja sih di MAM Limpung? Mulai dari belajar yang asik, eskul yang pecah, sampai event-event keren ada di sini. Cek koleksi fotonya!
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-16 relative z-20">
        
        <!-- Filters (Scrollable horizontally on mobile, sharp borders) -->
        <div class="w-full overflow-x-auto no-scrollbar relative z-30 mb-16 pb-2 border-b border-slate-200">
            <div class="flex gap-4 min-w-max px-4 md:justify-center">
                <template x-for="category in categories" :key="category">
                    <button 
                        @click="activeCategory = category"
                        class="px-4 py-2 text-sm font-bold uppercase tracking-widest transition-all duration-300 border-b-2"
                        :class="activeCategory === category ? 'border-blue-900 text-blue-900' : 'border-transparent text-slate-400 hover:text-slate-800'"
                    >
                        <span x-text="category"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- The Card Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-12">
            <template x-for="album in filteredAlbums" :key="album.id">
                
                <!-- Wrapper for Messy Stack Effect -->
                <div 
                    @click="openLightbox(album)"
                    class="group relative cursor-pointer pt-4 px-2"
                    x-show="true"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-8"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    <!-- Background Messy Cards (Only show if multiple photos) -->
                    <template x-if="album.images.length > 1">
                        <div class="absolute inset-0 z-0 top-4">
                            <!-- Card 1 (Rotated Right) -->
                            <div class="absolute inset-0 bg-white border border-slate-200 transform rotate-6 scale-[0.98] shadow-sm transition-all duration-500 group-hover:rotate-12 group-hover:translate-x-3 group-hover:-translate-y-2 origin-bottom-right"></div>
                            <!-- Card 2 (Rotated Left) -->
                            <div class="absolute inset-0 bg-white border border-slate-200 transform -rotate-3 scale-[0.98] shadow-sm transition-all duration-500 group-hover:-rotate-6 group-hover:-translate-x-3 group-hover:-translate-y-1 origin-bottom-left"></div>
                        </div>
                    </template>

                    <!-- Main Sharp Card -->
                    <div class="relative z-10 bg-white p-3 border border-slate-200 shadow-sm group-hover:-translate-y-3 group-hover:shadow-xl transition-all duration-500 flex flex-col h-full">
                        
                        <!-- Image Container (Square/Polaroid aesthetic) -->
                        <div class="relative w-full aspect-square overflow-hidden bg-slate-100 border border-slate-100">
                            <!-- Show first image -->
                            <img :src="album.images[0]" :alt="album.title" class="absolute inset-0 w-full h-full object-cover grayscale contrast-125 group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700 ease-in-out">
                            
                            <!-- Overlay Count Badge for Albums -->
                            <div x-show="album.images.length > 1" class="absolute top-3 right-3 bg-blue-900 text-white text-[10px] font-bold px-2 py-1 flex items-center gap-1 shadow-md">
                                <i class="fa-solid fa-images"></i> <span x-text="album.images.length + ' Foto'"></span>
                            </div>

                            <!-- Category Badge -->
                            <div class="absolute bottom-3 left-3 bg-white text-slate-900 border border-slate-200 text-[9px] font-bold px-2 py-1 uppercase tracking-widest shadow-sm">
                                <span x-text="album.category"></span>
                            </div>
                        </div>

                        <!-- Card Info -->
                        <div class="pt-4 pb-2 text-left flex-grow flex flex-col justify-between">
                            <h3 class="font-bold text-slate-800 text-lg leading-tight mb-2 group-hover:text-amber-600 transition-colors" x-text="album.title"></h3>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                <span x-text="album.date"></span>
                            </p>
                        </div>
                    </div>
                </div>

            </template>
        </div>

        <!-- Empty State -->
        <div x-show="filteredAlbums.length === 0" class="text-center py-24 border border-dashed border-slate-300 mt-8" style="display: none;">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-100 text-slate-400 mb-4">
                <i class="fa-solid fa-ghost text-2xl"></i>
            </div>
            <h3 class="text-2xl font-black uppercase tracking-tighter text-slate-700">Kosong Melompong</h3>
            <p class="text-slate-500 mt-2 text-sm">Belum ada foto buat kategori ini. Coba cek yang lain!</p>
            <button @click="activeCategory = 'Semua'" class="mt-6 px-6 py-3 bg-blue-900 text-white font-bold uppercase tracking-widest hover:bg-amber-500 hover:text-blue-900 transition-colors text-xs border border-blue-900">Balik ke Semua</button>
        </div>

    </div>

    <!-- Elegant Minimalist Lightbox -->
    <div x-show="lightboxOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/90 backdrop-blur-sm p-0 md:p-8" style="display: none;" x-transition.opacity>
        
        <!-- Elegant Container -->
        <div class="relative w-full h-full max-w-7xl max-h-screen md:max-h-[85vh] bg-white flex flex-col md:flex-row overflow-hidden shadow-2xl border border-slate-800" @click.away="closeLightbox()">
            
            <!-- Close Button (Inside the container for mobile, absolute for desktop) -->
            <button @click="closeLightbox()" class="absolute top-4 right-4 md:top-6 md:right-6 w-10 h-10 bg-slate-100 hover:bg-amber-500 hover:text-blue-900 text-slate-900 flex items-center justify-center transition-colors z-50 shadow-md cursor-pointer">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

            <!-- Left: Main Image Display -->
            <div class="w-full md:w-2/3 bg-slate-50 relative flex items-center justify-center h-[50vh] md:h-full group">
                <img :src="currentImage" class="absolute inset-0 w-full h-full object-contain p-2 md:p-8">
                
                <!-- Top Control Buttons (Play/Pause & Download) -->
                <div class="absolute top-4 left-4 md:top-6 md:left-6 flex gap-2 z-30">
                    <!-- Play/Pause Button -->
                    <button x-show="currentAlbum?.images.length > 1" @click.stop="toggleAutoplay()" 
                        class="w-10 h-10 bg-black/25 hover:bg-black/55 text-white rounded-none flex items-center justify-center transition-all duration-200 cursor-pointer shadow-md"
                        :title="autoplayActive ? 'Hentikan Otomatis' : 'Mulai Otomatis'">
                        <i class="fa-solid" :class="autoplayActive ? 'fa-pause' : 'fa-play'"></i>
                    </button>
                    
                    <!-- Download Button -->
                    <a :href="currentImage" download target="_blank"
                        class="w-10 h-10 bg-black/25 hover:bg-black/55 text-white rounded-none flex items-center justify-center transition-all duration-200 cursor-pointer shadow-md"
                        title="Unduh Foto">
                        <i class="fa-solid fa-download"></i>
                    </a>
                </div>

                <!-- Slide Left Button (Transparent/Hover visible) -->
                <button x-show="currentAlbum?.images.length > 1" @click.stop="prevImageManual()" 
                    class="absolute left-2 group-hover:left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/15 hover:bg-black/50 text-white rounded-none flex items-center justify-center transition-all duration-300 z-30 cursor-pointer"
                    title="Foto Sebelumnya">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>

                <!-- Slide Right Button (Transparent/Hover visible) -->
                <button x-show="currentAlbum?.images.length > 1" @click.stop="nextImageManual()" 
                    class="absolute right-2 group-hover:right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/15 hover:bg-black/50 text-white rounded-none flex items-center justify-center transition-all duration-300 z-30 cursor-pointer"
                    title="Foto Berikutnya">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </button>

                <!-- Border-like loading bar -->
                <div x-show="autoplayActive && currentAlbum?.images.length > 1" class="absolute bottom-0 left-0 w-full h-[4px] bg-slate-200/50 z-30">
                    <div class="h-full bg-amber-500 transition-all ease-linear" :style="'width: ' + progress + '%'; transitionDuration: '50ms'"></div>
                </div>
            </div>

            <!-- Right: Info & Thumbnails -->
            <div class="w-full md:w-1/3 flex flex-col h-[50vh] md:h-full bg-white border-t md:border-t-0 md:border-l border-slate-200 p-6 md:p-10 overflow-y-auto no-scrollbar">
                
                <div class="mb-6">
                    <span class="text-amber-600 text-[10px] font-bold uppercase tracking-widest border border-amber-600 px-2 py-1 mb-4 inline-block" x-text="currentAlbum?.category"></span>
                    <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tighter text-slate-900 leading-none mb-3" x-text="currentAlbum?.title"></h2>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest"><i class="fa-regular fa-calendar mr-1"></i> <span x-text="currentAlbum?.date"></span></p>
                </div>
                
                <div class="prose prose-sm text-slate-600 mb-8 leading-relaxed">
                    <p x-text="currentAlbum?.description || ''"></p>
                </div>
                
                <!-- Thumbnails Gallery for Albums -->
                <div x-show="currentAlbum?.images.length > 1" class="mt-auto pt-6 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Isi Album (<span x-text="currentAlbum?.images.length"></span>)</h4>
                        <div class="text-xs text-slate-355">Tap gambar</div>
                    </div>
                    
                    <div class="grid grid-cols-4 gap-2">
                        <template x-for="(img, index) in currentAlbum?.images" :key="index">
                            <button @click="selectImageManual(img)" class="relative aspect-square border-2 transition-all duration-300 cursor-pointer"
                                    :class="currentImage === img ? 'border-amber-500 p-0.5' : 'border-transparent opacity-60 hover:opacity-100'">
                                <img :src="img" class="absolute inset-0 w-full h-full object-cover bg-slate-100">
                            </button>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Extra styles for horizontal scrollbar hiding -->
<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('gallery', () => ({
        activeCategory: 'Semua',
        categories: @json($categories),
        lightboxOpen: false,
        currentAlbum: null,
        currentImage: null,
        
        albums: @json($albums),
        
        // Slideshow states
        slideshowInterval: null,
        progress: 0,
        autoplayActive: false,
        
        get filteredAlbums() {
            if (this.activeCategory === 'Semua') {
                return this.albums;
            }
            return this.albums.filter(album => album.category === this.activeCategory);
        },
        
        openLightbox(album) {
            this.currentAlbum = album;
            this.currentImage = album.images[0]; // Set foto pertama sebagai tampilan utama
            this.lightboxOpen = true;
            document.body.style.overflow = 'hidden';
            
            // Start autoplay slideshow
            setTimeout(() => {
                this.startAutoplay();
            }, 100);
        },
        
        closeLightbox() {
            this.lightboxOpen = false;
            document.body.style.overflow = 'auto';
            this.stopAutoplay();
            
            setTimeout(() => {
                if(!this.lightboxOpen) {
                    this.currentAlbum = null;
                    this.currentImage = null;
                }
            }, 300);
        },

        nextImage() {
            if (!this.currentAlbum) return;
            const images = this.currentAlbum.images;
            const currentIndex = images.indexOf(this.currentImage);
            const nextIndex = (currentIndex + 1) % images.length;
            this.currentImage = images[nextIndex];
        },

        prevImage() {
            if (!this.currentAlbum) return;
            const images = this.currentAlbum.images;
            const currentIndex = images.indexOf(this.currentImage);
            const prevIndex = (currentIndex - 1 + images.length) % images.length;
            this.currentImage = images[prevIndex];
        },

        nextImageManual() {
            this.nextImage();
            this.resetAutoplay();
        },

        prevImageManual() {
            this.prevImage();
            this.resetAutoplay();
        },

        selectImageManual(img) {
            this.currentImage = img;
            this.resetAutoplay();
        },

        startAutoplay() {
            if (this.slideshowInterval) clearInterval(this.slideshowInterval);
            if (!this.currentAlbum || this.currentAlbum.images.length <= 1) {
                this.progress = 0;
                this.autoplayActive = false;
                return;
            }
            
            this.progress = 0;
            this.autoplayActive = true;
            
            const tickRate = 50; // ms
            const duration = 6000; // 3 seconds
            const increment = (tickRate / duration) * 100;

            this.slideshowInterval = setInterval(() => {
                if (!this.lightboxOpen) {
                    this.stopAutoplay();
                    return;
                }
                
                if (this.autoplayActive) {
                    this.progress += increment;
                    if (this.progress >= 100) {
                        this.progress = 0;
                        this.nextImage();
                    }
                }
            }, tickRate);
        },

        stopAutoplay() {
            if (this.slideshowInterval) {
                clearInterval(this.slideshowInterval);
                this.slideshowInterval = null;
            }
            this.progress = 0;
            this.autoplayActive = false;
        },

        toggleAutoplay() {
            if (this.autoplayActive) {
                this.stopAutoplay();
            } else {
                this.startAutoplay();
            }
        },

        resetAutoplay() {
            this.progress = 0;
            if (this.autoplayActive && this.currentAlbum && this.currentAlbum.images.length > 1) {
                this.startAutoplay();
            } else {
                this.stopAutoplay();
            }
        }
    }));
});
</script>
@endsection
