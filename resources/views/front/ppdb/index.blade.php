@extends('layouts.app')

@section('content')

    <!-- Inject Inter Font & Manual Animation Styles -->
    @include("front.ppdb.partials.landingpage.style")

    <div class="font-inter bg-gray-50 pb-24">
 
        @include("front.ppdb.partials.landingpage.hero")

        <!-- Section: Alur Pendaftaran (Timeline Roadmap) -->
        @include("front.ppdb.partials.landingpage.timeline")

        <!-- Section: Persyaratan Dokumen -->
        @include("front.ppdb.partials.landingpage.berkas")

        <!-- Section: Program Beasiswa (Simple & Professional 3-Column Grid) -->
        @include("front.ppdb.partials.landingpage.beasiswa")

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
        @include("front.ppdb.partials.landingpage.biaya")

    </div>

@php
    $waNumber = str_replace(' ', '', str_replace('+', '', $siteSetting['whatsapp'] ?? '+628123456789'));
    $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode('Halo, saya ingin menanyakan tentang pendaftaran PPDB.');
@endphp

{{-- Mobile Fixed Action Bar --}}
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50"
     style="padding-bottom: env(safe-area-inset-bottom);"
     x-data="{ expanded: false }">

    {{-- Collapsed state: single pill --}}
    <div class="flex justify-center pb-4 px-4" x-show="!expanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <button @click="expanded = true"
                class="flex items-center gap-2.5 bg-blue-900/90 text-white text-sm font-semibold px-5 py-3 rounded-full shadow-lg active:scale-95 transition-transform duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-backpack2" viewBox="0 0 16 16">
                <path d="M4.04 7.43a4 4 0 0 1 7.92 0 .5.5 0 1 1-.99.14 3 3 0 0 0-5.94 0 .5.5 0 1 1-.99-.14"/>
                <path fill-rule="evenodd" d="M4 9.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm1 .5v3h6v-3h-1v.5a.5.5 0 0 1-1 0V10z"/>
                <path d="M6 2.341V2a2 2 0 1 1 4 0v.341c2.33.824 4 3.047 4 5.659v1.191l1.17.585a1.5 1.5 0 0 1 .83 1.342V13.5a1.5 1.5 0 0 1-1.5 1.5h-1c-.456.607-1.182 1-2 1h-7a2.5 2.5 0 0 1-2-1h-1A1.5 1.5 0 0 1 0 13.5v-2.382a1.5 1.5 0 0 1 .83-1.342L2 9.191V8a6 6 0 0 1 4-5.659M7 2v.083a6 6 0 0 1 2 0V2a1 1 0 0 0-2 0M3 13.5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5V8A5 5 0 0 0 3 8zm-1-3.19-.724.362a.5.5 0 0 0-.276.447V13.5a.5.5 0 0 0 .5.5H2zm12 0V14h.5a.5.5 0 0 0 .5-.5v-2.382a.5.5 0 0 0-.276-.447L14 10.309Z"/>
            </svg>
            Pendaftaran Dibuka
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
            </svg>
        </button>
    </div>

    {{-- Expanded state --}}
    <div x-show="expanded"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="bg-white border-t border-gray-100 shadow-2xl px-5 pt-4 pb-5">

        {{-- Drag handle + close --}}
        <div class="flex items-center justify-between mb-4">
            <div class="w-8 h-1 rounded-full bg-gray-200 mx-auto"></div>
            <button @click="expanded = false" class="ml-auto text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>

        {{-- Label --}}
        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Mulai dari sini</p>

        {{-- Buttons --}}
        <div class="flex gap-3">

            {{-- Daftar --}}
            <a href="{{ route('frontend.ppdb.form') }}"
               class="flex-1 flex items-center justify-center gap-2 bg-cyan-900 hover:bg-cyan-800 text-white text-sm font-semibold py-3.5 rounded-sm active:scale-95 transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Daftar Sekarang
            </a>

            {{-- WhatsApp --}}
            <a href="{{ $waUrl }}"
               target="_blank" rel="noreferrer"
               class="flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-3.5 px-4 rounded-sm active:scale-95 transition-all duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                    <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                </svg>
                Tanya
            </a>

        </div>
    </div>

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


@endsection