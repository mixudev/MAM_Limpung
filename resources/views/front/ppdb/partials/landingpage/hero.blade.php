        <!-- Fullscreen Image Slider Hero -->
        <section class="relative h-[70vh] w-full overflow-hidden bg-gray-900 group" 
             x-data="{ 
                activeSlide: 0, 
                isFullScreen: false,
                slides: [
                    'https://images.unsplash.com/photo-1523050853063-915894612264?q=80&w=2070&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=2104&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1541339907198-e087563f02b1?q=80&w=2070&auto=format&fit=crop'
                ],
                init() {
                    setInterval(() => {
                        if (!this.isFullScreen) {
                            this.activeSlide = (this.activeSlide === this.slides.length - 1) ? 0 : this.activeSlide + 1;
                        }
                    }, 5000);
                },
                downloadImage() {
                    const link = document.createElement('a');
                    link.href = this.slides[this.activeSlide];
                    link.download = 'Brosur_MAM_Limpung_Gambar_' + (this.activeSlide + 1) + '.jpg';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
             }">
             
            <!-- Slides -->
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="activeSlide === index" 
                     x-transition:enter="transition-opacity duration-1000 ease-in-out" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     x-transition:leave="transition-opacity duration-1000 ease-in-out" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0" 
                     class="absolute inset-0 w-full h-full">
                    <img :src="slide" class="w-full h-full object-cover opacity-60" alt="Slider MAM Limpung">
                </div>
            </template>
            
            <!-- Overlay Gradient (Subtle, just to make buttons/dots visible) -->
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 via-transparent to-gray-900/40"></div>

            <!-- Action Buttons (Eye & Download) -->
            <div class="absolute top-6 right-6 flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3 z-30">
                <button @click="isFullScreen = true" class="bg-black/50 hover:bg-blue-600 text-white p-3 rounded shadow-lg flex items-center justify-center group/btn" title="Lihat Layar Penuh">
                    <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"></path></svg>
                </button>
                <button @click="downloadImage()" class="bg-black/50 hover:bg-emerald-600 text-white p-3 rounded shadow-lg flex items-center justify-center group/btn" title="Download Gambar">
                    <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </button>
            </div>
            
            <!-- Slide Indicators -->
            <div class="absolute bottom-24 left-0 w-full flex justify-center space-x-2 z-10">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="activeSlide = index" class="w-2.5 h-2.5 rounded transition-all duration-300 shadow-md" :class="activeSlide === index ? 'bg-blue-500 scale-125' : 'bg-white/70 hover:bg-white'"></button>
                </template>
            </div>

            <!-- Fullscreen Modal -->
            <template x-teleport="body">
                <div x-show="isFullScreen" 
                     class="fixed inset-0 z-[100] bg-black/95 flex items-center justify-center p-4 backdrop-blur-sm"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     style="display: none;">
                    
                    <!-- Toolbar Fullscreen -->
                    <div class="absolute top-6 right-6 flex space-x-4 z-50">
                        <button @click="downloadImage()" class="text-white hover:text-emerald-400 bg-gray-900/50 p-3 rounded transition-colors" title="Download Gambar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </button>
                        <button @click="isFullScreen = false" class="text-white hover:text-red-500 bg-gray-900/50 p-3 rounded transition-colors" title="Tutup Fullscreen">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Image Container -->
                    <img :src="slides[activeSlide]" class="max-w-full max-h-full object-contain rounded-md drop-shadow-2xl" @click.away="isFullScreen = false" alt="Fullscreen Brosur">
                    
                    <!-- Navigation Arrows Fullscreen -->
                    <button @click.stop="activeSlide = (activeSlide === 0) ? slides.length - 1 : activeSlide - 1" class="absolute left-6 top-1/2 -translate-y-1/2 text-white hover:text-blue-400 bg-gray-900/50 p-4 rounded transition-colors hidden md:block">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button @click.stop="activeSlide = (activeSlide === slides.length - 1) ? 0 : activeSlide + 1" class="absolute right-6 top-1/2 -translate-y-1/2 text-white hover:text-blue-400 bg-gray-900/50 p-4 rounded transition-colors hidden md:block">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </template>
        </section>

        <!-- Small Action Box (Overlapping) -->
        <section class="relative z-20 -mt-14 mb-16 container mx-auto px-6 max-w-4xl">
            <div class="bg-white p-6 shadow-md border border-gray-100 rounded-md flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="mb-4 md:mb-0 text-center md:text-left flex-grow">
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Siap Bergabung Bersama Kami?</h2>
                    <p class="text-gray-500 text-xs font-medium">Lengkapi biodata dan persyaratan melalui formulir online.</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <a href="{{ route('frontend.ppdb.status') }}" class="px-5 py-3.5 border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 hover:text-blue-900 transition-colors text-center text-xs tracking-wide rounded">
                        Cek Status
                    </a>
                    @php
                        $today = date('Y-m-d');
                        $isWaveActive = false;
                        foreach ($waves as $wave) {
                            if ($today >= $wave['start_date'] && $today <= $wave['end_date']) {
                                $isWaveActive = true;
                                break;
                            }
                        }
                        $isOpen = $general['is_open'] && (empty($waves) || $isWaveActive);
                    @endphp
                    @if($isOpen)
                        <a href="{{ route('frontend.ppdb.form') }}" class="bg-blue-600 text-white px-6 py-3.5 font-semibold hover:bg-blue-700 transition-colors text-center text-xs tracking-wide rounded">
                            Isi Formulir Sekarang
                        </a>
                    @else
                        <button disabled class="bg-gray-400 text-white px-6 py-3.5 font-semibold cursor-not-allowed text-center text-xs tracking-wide rounded shadow-sm">
                            Pendaftaran Ditutup
                        </button>
                    @endif
                </div>
            </div>
        </section>

        <!-- Sticky Secondary Navigation Bar (Native Alpine Events & Methods) -->
        <div x-data="{ 
                activeSection: 'alur-pendaftaran',
                mobileMenuOpen: false,
                sections: [
                    { id: 'alur-pendaftaran', label: 'Alur & Jadwal', icon: 'fa-route' },
                    { id: 'persyaratan', label: 'Persyaratan Dokumen', icon: 'fa-file-signature' },
                    { id: 'beasiswa', label: 'Program Beasiswa', icon: 'fa-award' },
                    { id: 'biaya', label: 'Rincian Biaya', icon: 'fa-wallet' }
                ],
                scrollToSection(id) {
                    const el = document.getElementById(id);
                    if (el) {
                        const offset = 120; 
                        const bodyRect = document.body.getBoundingClientRect().top;
                        const elementRect = el.getBoundingClientRect().top;
                        const elementPosition = elementRect - bodyRect;
                        const offsetPosition = elementPosition - offset;
                        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                    this.mobileMenuOpen = false;
                },
                getActiveLabel() {
                    const found = this.sections.find(s => s.id === this.activeSection);
                    return found ? found.label : 'Menu Halaman';
                },
                getActiveIcon() {
                    const found = this.sections.find(s => s.id === this.activeSection);
                    return found ? found.icon : 'fa-list';
                },
                updateActive() {
                    // Check if scrolled to the absolute bottom of the document
                    if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight - 10)) {
                        this.activeSection = 'biaya';
                        return;
                    }
                    
                    let current = 'alur-pendaftaran';
                    this.sections.forEach(sec => {
                        const el = document.getElementById(sec.id);
                        if (el) {
                            const rect = el.getBoundingClientRect();
                            if (rect.top <= 165) {
                                current = sec.id;
                            }
                        }
                    });
                    this.activeSection = current;
                }
             }" 
             x-init="updateActive()"
             @scroll.window="updateActive()"
             @resize.window="updateActive()"
             class="static md:sticky md:top-[64px] z-[40] bg-white border-b border-gray-200/80 shadow-sm transition-all duration-300">
            <div class="max-w-6xl mx-auto px-6 py-2.5">
                
                <!-- Desktop Viewport: Tabs -->
                <div class="hidden md:flex justify-center gap-2">
                    <template x-for="sec in sections" :key="sec.id">
                        <a :href="'#' + sec.id" 
                           @click.prevent="scrollToSection(sec.id)"
                           :class="activeSection === sec.id ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50 border border-transparent'"
                           class="px-4 py-2 text-xs font-semibold transition-all duration-200 whitespace-nowrap rounded flex items-center gap-1.5">
                            <i :class="'fa-solid ' + sec.icon"></i>
                            <span x-text="sec.label"></span>
                        </a>
                    </template>
                </div>

                <!-- Mobile Viewport: Compact Collapsible Dropdown -->
                <div class="md:hidden relative" x-on:click.away="mobileMenuOpen = false">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="w-full flex items-center justify-between bg-gray-50 border border-gray-200 px-4 py-2.5 rounded text-xs font-semibold text-gray-700 hover:bg-gray-100 transition-colors">
                        <span class="flex items-center gap-2">
                            <i :class="'fa-solid text-blue-600 ' + getActiveIcon()"></i>
                            <span x-text="getActiveLabel()"></span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-200" :class="mobileMenuOpen ? 'rotate-180 text-blue-600' : ''"></i>
                    </button>
                    
                    <!-- Dropdown Options Overlay -->
                    <div x-show="mobileMenuOpen" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-md shadow-lg z-50 overflow-hidden divide-y divide-gray-100"
                         style="display: none;">
                        <template x-for="sec in sections" :key="sec.id">
                            <button @click="scrollToSection(sec.id)" 
                                    class="w-full text-left px-4 py-3 text-xs font-semibold flex items-center gap-3 transition-colors"
                                    :class="activeSection === sec.id ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'">
                                <i :class="'fa-solid w-4 text-center ' + sec.icon + (activeSection === sec.id ? ' text-blue-600' : ' text-gray-400')"></i>
                                <span x-text="sec.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

            </div>
        </div>