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