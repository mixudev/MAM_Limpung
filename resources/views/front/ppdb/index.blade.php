@extends('layouts.app')

@section('content')

    <!-- Inject Inter Font & Manual Animation Styles -->
    <style>
        
        .font-inter { font-family: 'Inter', sans-serif; }

        /* Manual GPU-Accelerated Animate on Scroll Styles */
        .fade-up-init {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .fade-left-init {
            opacity: 0;
            transform: translateX(-20px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .fade-right-init {
            opacity: 0;
            transform: translateX(20px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .zoom-in-init {
            opacity: 0;
            transform: scale(0.96);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .animate-show {
            opacity: 1 !important;
            transform: none !important;
        }
    </style>

    <div class="font-inter bg-gray-50 pb-24">
 
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
                        $isOpen = $general['is_open'] && ($today >= $general['start_date'] && $today <= $general['end_date']);
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

        <!-- Section: Alur Pendaftaran (Timeline Roadmap) -->
        <section id="alur-pendaftaran" class="ppdb-section scroll-mt-[130px] py-16 bg-gradient-to-b from-gray-50 to-white overflow-hidden">
            <div class="max-w-6xl mx-auto px-6">
                <!-- Title -->
                <div class="text-center max-w-2xl mx-auto mb-12 fade-up-init">
                    <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2.5 py-1 rounded-sm uppercase tracking-wider">Timeline PPDB</span>
                    <h2 class="text-2xl font-bold text-gray-900 mt-2.5 leading-tight">Peta Jalan Pendaftaran</h2>
                    <p class="text-gray-500 mt-2 text-xs md:text-sm">Ikuti tahapan seleksi PPDB MAS Muhammadiyah Limpung dengan mudah dan terencana.</p>
                </div>

                <!-- Roadmap Content Wrapper -->
                <div class="relative">
                    
                    <!-- Dynamic Roadmap Winding SVG Path (Snakes on Desktop, Straight on Mobile, Stops exactly at last Node) -->
                    <svg id="roadmap-svg" class="absolute inset-0 w-full h-full pointer-events-none z-10" fill="none">
                        <path id="roadmap-path" stroke="url(#roadmap-grad)" stroke-width="4" stroke-linecap="round" stroke-dasharray="8 6" />
                        <defs>
                            <linearGradient id="roadmap-grad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#3b82f6" />
                                <stop offset="35%" stop-color="#10b981" />
                                <stop offset="70%" stop-color="#f59e0b" />
                                <stop offset="100%" stop-color="#8b5cf6" />
                            </linearGradient>
                        </defs>
                    </svg>

                    <!-- Steps -->
                    <div class="space-y-12 relative z-10">
                        
                        @forelse($waves as $index => $wave)
                            @php
                                $isEven = $index % 2 == 0;
                                $colors = ['blue', 'emerald', 'amber', 'violet', 'rose', 'cyan'];
                                $color = $colors[$index % count($colors)];
                                $number = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                                $startDate = \Carbon\Carbon::parse($wave['start_date'])->translatedFormat('d M Y');
                                $endDate = \Carbon\Carbon::parse($wave['end_date'])->translatedFormat('d M Y');
                            @endphp
                            <!-- Dynamic Wave Step -->
                            <div class="flex flex-col md:flex-row{{ !$isEven ? '-reverse' : '' }} items-center justify-start md:justify-between relative min-h-[140px]">
                                <!-- Card -->
                                <div class="w-full md:w-[42%] pl-12 md:pl-0 fade-{{ $isEven ? 'right' : 'left' }}-init">
                                    <div class="bg-white p-6 rounded-md shadow-sm border border-gray-200/80">
                                        <span class="text-{{ $color }}-600 font-bold text-xs tracking-wider block mb-1 uppercase">{{ $startDate }} - {{ $endDate }}</span>
                                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-1.5">
                                            {{ $wave['name'] }}
                                        </h3>
                                        <p class="text-gray-500 mt-2 text-xs leading-relaxed">Periode pendaftaran dibuka untuk tahun ajaran {{ $general['tahun_ajaran'] }}. Segera lengkapi persyaratan Anda.</p>
                                    </div>
                                </div>
                                <!-- Center Checkpoint Node -->
                                <div id="node-{{ $index + 1 }}" class="absolute left-6 md:left-[{{ $isEven ? '48' : '52' }}%] -translate-x-1/2 w-5 h-5 rounded-full bg-{{ $color }}-500 border-2 border-white shadow-sm flex items-center justify-center {{ $index === 0 ? 'animate-pulse' : '' }} z-20">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                </div>
                                <!-- Spacer (Desktop) -->
                                <div class="hidden md:block w-[42%] text-{{ $isEven ? 'left' : 'right' }} {{ $isEven ? 'pl-10' : 'pr-10' }}">
                                    <div class="text-3xl font-bold text-{{ $color }}-100/80">{{ $number }}</div>
                                    <div class="text-[10px] font-bold text-{{ $color }}-600 uppercase tracking-widest mt-0.5">Tahap {{ $number }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 text-sm">Jadwal gelombang pendaftaran belum ditentukan oleh Admin.</div>
                        @endforelse

                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Persyaratan Dokumen -->
        <section id="persyaratan" class="ppdb-section scroll-mt-[130px] py-16 bg-gray-50">
            <div class="max-w-6xl mx-auto px-6">
                
                <!-- Header -->
                <div class="text-center max-w-2xl mx-auto mb-12 fade-up-init">
                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-sm uppercase tracking-wider">Persyaratan Dokumen</span>
                    <h2 class="text-2xl font-bold text-gray-900 mt-2.5 leading-tight">Berkas Yang Diperlukan</h2>
                    <p class="text-gray-500 mt-2 text-xs md:text-sm">Lengkapi berkas-berkas berikut untuk mempermudah panitia memverifikasi data pendaftaran Anda.</p>
                </div>

                <!-- Simple Table -->
                <div class="overflow-x-auto bg-white rounded-md shadow-sm border border-gray-200">
                    <table class="w-full text-left border-collapse min-w-[500px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="py-3 px-4 md:px-6 font-bold text-gray-900 text-xs md:text-sm">Nama Dokumen</th>
                                <th class="py-3 px-4 md:px-6 font-bold text-gray-900 text-xs md:text-sm">Keterangan</th>
                                <th class="py-3 px-4 md:px-6 font-bold text-gray-900 text-xs md:text-sm w-20">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($requirements as $req)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 md:px-6 text-xs md:text-sm font-semibold text-gray-900">{{ $req['label'] }}</td>
                                    <td class="py-3 px-4 md:px-6 text-xs text-gray-600">{{ $req['required'] ? 'Wajib disiapkan oleh semua siswa' : 'Opsional / sesuai program pilihan' }}</td>
                                    <td class="py-3 px-4 md:px-6 text-xs font-semibold">
                                        <span class="inline-block px-2 py-1 rounded text-white {{ $req['required'] ? 'bg-emerald-600' : 'bg-amber-500' }}">
                                            {{ $req['required'] ? 'Wajib' : 'Opsional' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 px-6 text-center text-xs text-gray-400">
                                        Tidak ada persyaratan berkas yang dikonfigurasi.
                                    </td>
                                </tr>
                            @endforelse

                            <!-- Berkas Tambahan Section Header -->
                            <tr class="bg-blue-50 border-b border-blue-200">
                                <td colspan="3" class="py-3 px-4 md:px-6 text-xs font-bold text-blue-900 uppercase tracking-wide">Berkas Tambahan (Untuk Program Khusus / Beasiswa)</td>
                            </tr>

                            <!-- Item 1 -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 md:px-6 text-xs md:text-sm font-semibold text-gray-900">Piagam / Sertifikat Prestasi</td>
                                <td class="py-3 px-4 md:px-6 text-xs text-gray-600">Untuk klaim Beasiswa Prestasi (Juara akademik/olahraga/kesenian min. Kabupaten)</td>
                                <td class="py-3 px-4 md:px-6 text-xs font-semibold"><span class="inline-block px-2 py-1 rounded text-white bg-blue-500">Opsional</span></td>
                            </tr>

                            <!-- Item 2 -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 md:px-6 text-xs md:text-sm font-semibold text-gray-900">Kartu KIP, PKH, KPS, atau KKS</td>
                                <td class="py-3 px-4 md:px-6 text-xs text-gray-600">Untuk verifikasi Beasiswa Afirmasi (Keluarga kurang mampu)</td>
                                <td class="py-3 px-4 md:px-6 text-xs font-semibold"><span class="inline-block px-2 py-1 rounded text-white bg-blue-500">Opsional</span></td>
                            </tr>

                            <!-- Item 3 -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 md:px-6 text-xs md:text-sm font-semibold text-gray-900">Surat Rekomendasi Ranting Muhammadiyah</td>
                                <td class="py-3 px-4 md:px-6 text-xs text-gray-600">Untuk kader Muhammadiyah mendapat potongan biaya khusus</td>
                                <td class="py-3 px-4 md:px-6 text-xs font-semibold"><span class="inline-block px-2 py-1 rounded text-white bg-blue-500">Opsional</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Info Note -->
                <div class="mt-6 bg-blue-50 border border-blue-200 p-4 rounded-md flex items-start gap-3">
                    <span class="text-blue-900 font-bold text-xs md:text-sm leading-relaxed">
                        Catatan: Semua berkas diserahkan dalam map kertas warna Kuning (Putra) atau Merah (Putri).
                    </span>
                </div>
            </div>
        </section>

        <!-- Section: Program Beasiswa (Simple & Professional 3-Column Grid) -->
        <section id="beasiswa" class="ppdb-section scroll-mt-[130px] py-16 bg-white">
            <div class="max-w-6xl mx-auto px-6">
                
                <!-- Header -->
                <div class="text-center max-w-2xl mx-auto mb-12 fade-up-init">
                    <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2.5 py-1 rounded-sm uppercase tracking-wider">Jalur Beasiswa</span>
                    <h2 class="text-2xl font-bold text-gray-900 mt-2.5 leading-tight">Program Beasiswa Siswa Baru</h2>
                    <p class="text-gray-500 mt-2 text-xs md:text-sm">Kami mendukung semangat belajar siswa berprestasi, penghafal Quran, maupun keluarga kurang mampu untuk tetap bisa meraih cita-cita.</p>
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block mt-8 overflow-x-auto bg-white rounded-md shadow-sm border border-gray-200">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="py-4 px-6 font-bold text-gray-900 text-sm">Jalur Beasiswa</th>
                                <th class="py-4 px-6 font-bold text-gray-900 text-sm">Kriteria & Persyaratan</th>
                                <th class="py-4 px-6 font-bold text-gray-900 text-sm">Benefit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-xs text-gray-600">
                            <!-- Beasiswa Prestasi -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 align-top">
                                    <div class="flex items-center gap-2 mb-1">
                                        <i class="fa-solid fa-trophy text-amber-500"></i>
                                        <span class="font-bold text-gray-900 text-sm">Prestasi</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-sm uppercase inline-block">Akademik & Bakat</span>
                                </td>
                                <td class="py-4 px-6 align-top">
                                    <ul class="space-y-1">
                                        <li class="flex items-start gap-1.5"><i class="fa-solid fa-check text-amber-500 mt-0.5"></i> Juara 1 Paralel SMP/MTs asal</li>
                                        <li class="flex items-start gap-1.5"><i class="fa-solid fa-check text-amber-500 mt-0.5"></i> Juara 2 & 3 Paralel SMP/MTs asal</li>
                                        <li class="flex items-start gap-1.5"><i class="fa-solid fa-check text-amber-500 mt-0.5"></i> Juara Lomba tingkat Kabupaten / Provinsi</li>
                                    </ul>
                                </td>
                                <td class="py-4 px-6 align-top font-semibold text-amber-600">
                                    <ul class="space-y-1 text-gray-900">
                                        <li><span class="text-amber-600">Bebas SPP 1 Tahun</span> (Juara 1)</li>
                                        <li><span class="text-amber-600">Bebas SPP 6 Bulan</span> (Juara 2 & 3)</li>
                                        <li><span class="text-amber-600">Reward Khusus</span> (Juara Lomba)</li>
                                    </ul>
                                </td>
                            </tr>
                            
                            <!-- Beasiswa Tahfidz -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 align-top">
                                    <div class="flex items-center gap-2 mb-1">
                                        <i class="fa-solid fa-book-quran text-emerald-500"></i>
                                        <span class="font-bold text-gray-900 text-sm">Tahfidz</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-sm uppercase inline-block">Keagamaan</span>
                                </td>
                                <td class="py-4 px-6 align-top">
                                    <ul class="space-y-1">
                                        <li class="flex items-start gap-1.5"><i class="fa-solid fa-check text-emerald-500 mt-0.5"></i> Hafal minimal 3 Juz</li>
                                        <li class="flex items-start gap-1.5"><i class="fa-solid fa-check text-emerald-500 mt-0.5"></i> Hafal minimal 5 Juz</li>
                                        <li class="flex items-start gap-1.5"><i class="fa-solid fa-check text-emerald-500 mt-0.5"></i> Hafal 10 Juz atau lebih</li>
                                    </ul>
                                </td>
                                <td class="py-4 px-6 align-top font-semibold">
                                    <ul class="space-y-1 text-gray-900">
                                        <li><span class="text-emerald-600">Bebas SPP 3 Bulan</span> (3 Juz)</li>
                                        <li><span class="text-emerald-600">Bebas SPP 6 Bulan</span> (5 Juz)</li>
                                        <li><span class="text-emerald-600">Bebas SPP 1 Tahun</span> (10+ Juz)</li>
                                    </ul>
                                </td>
                            </tr>

                            <!-- Beasiswa Afirmasi -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 align-top">
                                    <div class="flex items-center gap-2 mb-1">
                                        <i class="fa-solid fa-handshake-angle text-indigo-500"></i>
                                        <span class="font-bold text-gray-900 text-sm">Afirmasi</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-sm uppercase inline-block">Kader & Sosial</span>
                                </td>
                                <td class="py-4 px-6 align-top">
                                    <ul class="space-y-1">
                                        <li class="flex items-start gap-1.5"><i class="fa-solid fa-check text-indigo-500 mt-0.5"></i> Siswa Yatim / Piatu</li>
                                        <li class="flex items-start gap-1.5"><i class="fa-solid fa-check text-indigo-500 mt-0.5"></i> Memiliki KIP / PIP / PKH</li>
                                        <li class="flex items-start gap-1.5"><i class="fa-solid fa-check text-indigo-500 mt-0.5"></i> Rekomendasi Kader / Ranting Muhammadiyah</li>
                                    </ul>
                                </td>
                                <td class="py-4 px-6 align-top font-semibold">
                                    <ul class="space-y-1 text-gray-900">
                                        <li><span class="text-indigo-600">Diskon SPP 50%</span> (Yatim/KIP)</li>
                                        <li><span class="text-indigo-600">Diskon SPP 50%</span> (Kader)</li>
                                        <li class="text-gray-500 font-normal italic mt-1">Berlaku selama masa pendidikan</li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden mt-8 space-y-4">
                    <!-- Beasiswa Prestasi Card -->
                    <div class="bg-white rounded-md shadow-sm border border-gray-200 p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-trophy text-amber-500 text-lg"></i>
                            <span class="font-bold text-gray-900 text-sm">Prestasi</span>
                        </div>
                        <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-sm uppercase inline-block mb-4">Akademik & Bakat</span>
                        
                        <div class="space-y-3 border-t border-gray-200 pt-4">
                            <div>
                                <h4 class="text-xs font-bold text-gray-700 mb-2">Kriteria:</h4>
                                <ul class="space-y-1 text-xs text-gray-600">
                                    <li>• Juara 1 Paralel SMP/MTs asal</li>
                                    <li>• Juara 2 & 3 Paralel SMP/MTs asal</li>
                                    <li>• Juara Lomba tingkat Kabupaten / Provinsi</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-amber-600 mb-2">Benefit:</h4>
                                <ul class="space-y-1 text-xs text-gray-900 font-semibold">
                                    <li>• Bebas SPP 1 Tahun (Juara 1)</li>
                                    <li>• Bebas SPP 6 Bulan (Juara 2 & 3)</li>
                                    <li>• Reward Khusus (Juara Lomba)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Beasiswa Tahfidz Card -->
                    <div class="bg-white rounded-md shadow-sm border border-gray-200 p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-book-quran text-emerald-500 text-lg"></i>
                            <span class="font-bold text-gray-900 text-sm">Tahfidz</span>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-sm uppercase inline-block mb-4">Keagamaan</span>
                        
                        <div class="space-y-3 border-t border-gray-200 pt-4">
                            <div>
                                <h4 class="text-xs font-bold text-gray-700 mb-2">Kriteria:</h4>
                                <ul class="space-y-1 text-xs text-gray-600">
                                    <li>• Hafal minimal 3 Juz</li>
                                    <li>• Hafal minimal 5 Juz</li>
                                    <li>• Hafal 10 Juz atau lebih</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-emerald-600 mb-2">Benefit:</h4>
                                <ul class="space-y-1 text-xs text-gray-900 font-semibold">
                                    <li>• Bebas SPP 3 Bulan (3 Juz)</li>
                                    <li>• Bebas SPP 6 Bulan (5 Juz)</li>
                                    <li>• Bebas SPP 1 Tahun (10+ Juz)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Beasiswa Afirmasi Card -->
                    <div class="bg-white rounded-md shadow-sm border border-gray-200 p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-handshake-angle text-indigo-500 text-lg"></i>
                            <span class="font-bold text-gray-900 text-sm">Afirmasi</span>
                        </div>
                        <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-sm uppercase inline-block mb-4">Kader & Sosial</span>
                        
                        <div class="space-y-3 border-t border-gray-200 pt-4">
                            <div>
                                <h4 class="text-xs font-bold text-gray-700 mb-2">Kriteria:</h4>
                                <ul class="space-y-1 text-xs text-gray-600">
                                    <li>• Siswa Yatim / Piatu</li>
                                    <li>• Memiliki KIP / PIP / PKH</li>
                                    <li>• Rekomendasi Kader / Ranting Muhammadiyah</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-indigo-600 mb-2">Benefit:</h4>
                                <ul class="space-y-1 text-xs text-gray-900 font-semibold">
                                    <li>• Diskon SPP 50% (Yatim/KIP)</li>
                                    <li>• Diskon SPP 50% (Kader)</li>
                                    <li class="text-gray-500 font-normal italic">Berlaku selama masa pendidikan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Section: Call To Action Button (Sleek Compact Horizontal Strip) -->
        <section id="daftar" class="py-6 bg-white">
            <div class="max-w-5xl mx-auto px-6 zoom-in-init">
                <!-- Long Narrow Compact Card -->
                <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-md p-6 flex flex-col md:flex-row items-center justify-between gap-4 relative overflow-hidden">
                    <!-- Accent background graphic -->
                    <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-white/5 to-transparent pointer-events-none"></div>
                    
                    <div class="text-center md:text-left flex-grow relative z-10">
                        <span class="text-amber-400 text-[10px] font-bold uppercase tracking-wider">Pendaftaran Online</span>
                        <h3 class="text-base md:text-lg font-bold text-white mt-0.5">Mulai Pendaftaran Online Sekarang</h3>
                        <p class="text-blue-200 text-xs mt-0.5 max-w-lg">Kuota pendaftaran terbatas di setiap gelombang. Isi formulir dalam 5 menit.</p>
                    </div>
                    
                    <div class="flex-shrink-0 w-full md:w-auto flex flex-col sm:flex-row gap-2 relative z-10">
                        <a href="{{ route('frontend.ppdb.form') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-6 py-3 rounded text-center text-xs tracking-wide shadow transition-all duration-200">
                            Isi Formulir Sekarang
                        </a>
                        <a href="{{ route('frontend.ppdb.status') }}" class="bg-white/10 hover:bg-white/15 text-white border border-white/20 font-semibold px-5 py-3 rounded text-center text-xs transition-all duration-200">
                            Lacak Status
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Keterangan Biaya & Detail Lain -->
        <section id="biaya" class="ppdb-section scroll-mt-[130px] py-16 bg-gray-50">
            <div class="max-w-6xl mx-auto px-6">
                
                <!-- Header -->
                <div class="text-center max-w-2xl mx-auto mb-12 fade-up-init">
                    <span class="bg-violet-100 text-violet-800 text-[10px] font-bold px-2.5 py-1 rounded-sm uppercase tracking-wider">Rincian Biaya</span>
                    <h2 class="text-2xl font-bold text-gray-900 mt-2.5 leading-tight">Transparansi Biaya Pendidikan</h2>
                    <p class="text-gray-500 mt-2 text-xs md:text-sm">Seluruh rincian biaya pendaftaran awal tertera secara transparan tanpa ada biaya tersembunyi.</p>
                </div>

                <div class="grid lg:grid-cols-12 gap-6 items-start">
                    
                    <!-- Price Summary Card (Left) -->
                    <div class="lg:col-span-5 bg-white p-6 rounded-md shadow-sm border border-gray-200/80 flex flex-col justify-between fade-right-init">
                        <div>
                            <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-sm uppercase tracking-wider">Total Daftar Ulang</span>
                            <div class="mt-3 flex items-baseline gap-2">
                                <span class="text-3xl font-bold text-gray-900">Rp 913.000</span>
                            </div>
                            <p class="text-gray-400 text-[10px] mt-1">Biaya dibayarkan satu kali saat dinyatakan lolos seleksi.</p>

                            <!-- Trust USP Badges -->
                            <div class="my-5 py-4 border-y border-gray-100 space-y-2.5">
                                <div class="flex items-center gap-3 text-xs font-semibold text-gray-700">
                                    <i class="fa-solid fa-building-circle-xmark text-emerald-500 text-sm"></i>
                                    <span>Bebas Uang Gedung / Uang Pembangunan</span>
                                </div>
                                <div class="flex items-center gap-3 text-xs font-semibold text-gray-700">
                                    <i class="fa-solid fa-coins text-emerald-500 text-sm"></i>
                                    <span>Bisa Diangsur 2 Kali</span>
                                </div>
                                <div class="flex items-center gap-3 text-xs font-semibold text-gray-700">
                                    <i class="fa-solid fa-circle-dollar-to-slot text-emerald-500 text-sm"></i>
                                    <span>Biaya Sama untuk Putra & Putri</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-emerald-50/50 p-3 rounded flex gap-2 border border-emerald-100">
                            <i class="fa-solid fa-shield-halved text-emerald-600 text-sm flex-shrink-0 mt-0.5"></i>
                            <p class="text-[10px] text-emerald-800 leading-normal">**Jaminan 100% Uang Kembali** apabila calon peserta didik dinyatakan tidak lulus di sekolah asal (SMP/MTs).</p>
                        </div>
                    </div>

                    <!-- Detailed Cost Items Grid (Right) -->
                    <div class="lg:col-span-7 bg-white p-6 rounded-md shadow-sm border border-gray-200/80 fade-left-init">
                        <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2"><i class="fa-solid fa-list-check text-blue-600"></i>Alokasi Biaya Terperinci</h3>
                        
                        <div class="divide-y divide-gray-100">
                            <!-- Item 1 -->
                            <div class="py-3 flex justify-between items-center gap-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center transition-all duration-200">
                                        <i class="fa-solid fa-calendar-check text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-xs md:text-sm">SPP Bulan Pertama (Juli)</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Sudah tercover untuk bulan awal masuk sekolah</p>
                                    </div>
                                </div>
                                <span class="font-bold text-gray-900 text-xs md:text-sm">Rp 175.000</span>
                            </div>

                            <!-- Item 2 -->
                            <div class="py-3 flex justify-between items-center gap-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center transition-all duration-200">
                                        <i class="fa-solid fa-shirt text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-xs md:text-sm">Bahan & Jahit Seragam</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Termasuk seragam Batik Eksklusif & HW</p>
                                    </div>
                                </div>
                                <span class="font-bold text-gray-900 text-xs md:text-sm">Rp 305.000</span>
                            </div>

                            <!-- Item 3 -->
                            <div class="py-3 flex justify-between items-center gap-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center transition-all duration-200">
                                        <i class="fa-solid fa-medal text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-xs md:text-sm">Atribut Lengkap Madrasah</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Dasi, Topi, Ikat Pinggang, Almamater, Kaos Kaki, dll.</p>
                                    </div>
                                </div>
                                <span class="font-bold text-gray-900 text-xs md:text-sm">Rp 180.000</span>
                            </div>

                            <!-- Item 4 -->
                            <div class="py-3 flex justify-between items-center gap-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center transition-all duration-200">
                                        <i class="fa-solid fa-people-group text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-xs md:text-sm">Kegiatan Orientasi (MATSAMA & FORTASI)</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Perlengkapan, makan siang, & kaos kegiatan</p>
                                    </div>
                                </div>
                                <span class="font-bold text-gray-900 text-xs md:text-sm">Rp 120.000</span>
                            </div>

                            <!-- Item 5 -->
                            <div class="py-3 flex justify-between items-center gap-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center transition-all duration-200">
                                        <i class="fa-solid fa-book-open text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-xs md:text-sm">Buku Pelajaran, LKS, & Map Dokumen</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Seluruh modul dan buku paket semester ganjil</p>
                                    </div>
                                </div>
                                <span class="font-bold text-gray-900 text-xs md:text-sm">Rp 133.000</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- FAQs Accordions (Using Alpine.js) -->
                <div class="mt-16 max-w-3xl mx-auto fade-up-init" x-data="{ activeFaq: null }">
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-6">Pertanyaan Umum (PPDB FAQ)</h3>
                    
                    <div class="space-y-3">
                        <!-- FAQ 1 -->
                        <div class="bg-white rounded-md border border-gray-150 overflow-hidden shadow-sm">
                            <button @click="activeFaq = (activeFaq === 1) ? null : 1" class="w-full text-left p-5 font-semibold text-gray-800 flex justify-between items-center gap-4 hover:bg-gray-50/50 transition-colors text-xs md:text-sm">
                                <span>Apakah pembayaran biaya daftar ulang bisa diangsur/dicicil?</span>
                                <i class="fa-solid text-gray-400 transition-transform duration-200 text-xs" :class="activeFaq === 1 ? 'fa-chevron-up text-blue-600' : 'fa-chevron-down'"></i>
                            </button>
                            <div x-show="activeFaq === 1" x-transition class="p-5 pt-0 border-t border-gray-50 text-xs text-gray-500 leading-relaxed bg-gray-50/30">
                                **Ya, tentu saja.** Kami sangat memahami kondisi wali siswa. Biaya daftar ulang sebesar Rp 913.000 dapat diangsur maksimal sebanyak 2 (dua) kali pembayaran. Cicilan pertama dibayarkan saat daftar ulang fisik, dan pelunasan dapat dilakukan pada pertengahan semester ganjil (sekitar bulan September). Silakan berkonsultasi langsung dengan Panitia PPDB di sekretariat.
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="bg-white rounded-md border border-gray-150 overflow-hidden shadow-sm">
                            <button @click="activeFaq = (activeFaq === 2) ? null : 2" class="w-full text-left p-5 font-semibold text-gray-800 flex justify-between items-center gap-4 hover:bg-gray-50/50 transition-colors text-xs md:text-sm">
                                <span>Bagaimana jika calon siswa mengundurkan diri karena diterima di sekolah negeri?</span>
                                <i class="fa-solid text-gray-400 transition-transform duration-200 text-xs" :class="activeFaq === 2 ? 'fa-chevron-up text-blue-600' : 'fa-chevron-down'"></i>
                            </button>
                            <div x-show="activeFaq === 2" x-transition class="p-5 pt-0 border-t border-gray-50 text-xs text-gray-500 leading-relaxed bg-gray-50/30">
                                Apabila calon siswa mengundurkan diri sebelum dimulainya tahun ajaran baru karena diterima di sekolah negeri, maka biaya daftar ulang yang telah disetorkan akan **dikembalikan secara penuh 100% tanpa potongan apa pun** setelah menyerahkan bukti tanda kelulusan sekolah negeri terkait. Kami berkomitmen untuk tidak memberatkan pihak orang tua murid.
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="bg-white rounded-md border border-gray-150 overflow-hidden shadow-sm">
                            <button @click="activeFaq = (activeFaq === 3) ? null : 3" class="w-full text-left p-5 font-semibold text-gray-800 flex justify-between items-center gap-4 hover:bg-gray-50/50 transition-colors text-xs md:text-sm">
                                <span>Di mana dan kapan penyerahan berkas persyaratan fisik dilakukan?</span>
                                <i class="fa-solid text-gray-400 transition-transform duration-200 text-xs" :class="activeFaq === 3 ? 'fa-chevron-up text-blue-600' : 'fa-chevron-down'"></i>
                            </button>
                            <div x-show="activeFaq === 3" x-transition class="p-5 pt-0 border-t border-gray-50 text-xs text-gray-500 leading-relaxed bg-gray-50/30">
                                Penyerahan berkas fisik beserta wawancara dilakukan langsung di **Sekretariat PPDB MAS Muhammadiyah Limpung** (Jl. Cokronegoro No.34, Gepor, Limpung) pada hari kerja Senin s.d. Sabtu pukul 08.00 - 13.00 WIB. Bagi pendaftar online, penyerahan berkas dapat dilakukan berbarengan dengan jadwal wawancara terverifikasi.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>

    <!-- Ultra-Lightweight Custom Animate on Scroll Script (0 Libraries, GPU-Optimized) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const animObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-show');
                    }
                });
            }, { 
                threshold: 0.05, 
                rootMargin: '0px 0px -40px 0px' 
            });
            
            document.querySelectorAll('.fade-up-init, .fade-left-init, .fade-right-init, .zoom-in-init').forEach(el => {
                animObserver.observe(el);
            });
        });
    </script>

    <!-- Real-Time Dynamic Roadmap SVG Path Drawer -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const drawRoad = () => {
                const nodes = [
                    document.getElementById('node-1'),
                    document.getElementById('node-2'),
                    document.getElementById('node-3'),
                    document.getElementById('node-4')
                ];
                const svgPath = document.getElementById('roadmap-path');
                const svgElement = document.getElementById('roadmap-svg');
                
                if (nodes.every(n => n) && svgPath && svgElement) {
                    const svgRect = svgElement.getBoundingClientRect();
                    
                    const coords = nodes.map(node => {
                        const rect = node.getBoundingClientRect();
                        return {
                            x: rect.left + rect.width / 2 - svgRect.left,
                            y: rect.top + rect.height / 2 - svgRect.top
                        };
                    });
                    
                    // Generate a gorgeous smooth S-curve snaking path
                    let d = `M ${coords[0].x},${coords[0].y}`;
                    for (let i = 0; i < coords.length - 1; i++) {
                        const p0 = coords[i];
                        const p1 = coords[i+1];
                        // Calculate a dynamic vertical midpoint
                        const midY = (p0.y + p1.y) / 2;
                        // Use cubic bezier control points that sweep gracefully left and right
                        d += ` C ${p0.x},${midY} ${p1.x},${midY} ${p1.x},${p1.y}`;
                    }
                    svgPath.setAttribute('d', d);
                }
            };
            
            // Register sync listeners for load, resize, and scroll
            window.addEventListener('load', drawRoad);
            window.addEventListener('resize', drawRoad);
            window.addEventListener('scroll', drawRoad);
            
            // Repeated fallback timers to align perfectly after animations settle
            setTimeout(drawRoad, 100);
            setTimeout(drawRoad, 300);
            setTimeout(drawRoad, 600);
            setTimeout(drawRoad, 1200);
            setTimeout(drawRoad, 2500);
        });
    </script>

@endsection