@extends('layouts.app')

@section('seo_title', 'Periodisasi Kepala Madrasah — MAM Limpung')
@section('seo_description', 'Rekam jejak kepemimpinan MAM Limpung dari masa ke masa. Kenali para kepala madrasah yang telah membawa perubahan dan kemajuan.')

@section('content')
<div class="bg-[#f4f6f8] pt-12 pb-0 font-sans">
    {{-- Hero --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="relative bg-slate-900 overflow-hidden border border-slate-800 p-10 md:p-16 flex flex-col md:flex-row items-center justify-between">
            <img src="{{ asset('assets/img/school.png') }}" alt="Periodisasi Kepala MAM Limpung" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/95 to-slate-900/80 z-10"></div>
            <div class="relative z-20 md:w-2/3">
                <div class="w-12 h-1.5 bg-amber-500 mb-6 shadow-lg shadow-amber-500/20"></div>
                <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter leading-tight mb-4 drop-shadow-md">
                    Periodisasi <span class="text-amber-500">Kepala</span>
                </h1>
                <p class="text-blue-100 text-base md:text-lg font-medium leading-relaxed">
                    Rekam jejak para pemimpin yang telah mengabdikan diri dan membawa MAM Limpung menjadi madrasah yang disegani.
                </p>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="text-center mb-14">
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter mb-2">Para Pemimpin</h2>
            <div class="w-16 h-1 bg-blue-900 mx-auto"></div>
            <p class="text-slate-500 mt-4 max-w-2xl mx-auto text-sm">Tujuh kepala madrasah yang pernah memimpin MAM Limpung dari masa ke masa.</p>
        </div>

        <div class="relative">
            <div class="absolute left-8 md:left-1/2 top-0 bottom-0 w-0.5 bg-slate-300 -translate-x-1/2"></div>

            @foreach ($periods as $index => $period)
            <div class="relative mb-8 last:mb-0" data-aos="{{ $loop->even ? 'fade-left' : 'fade-right' }}" data-aos-delay="{{ $index * 80 }}">
                <div class="flex flex-col md:flex-row items-start gap-6 md:gap-0 {{ $loop->even ? 'md:flex-row-reverse' : '' }}">
                    <div class="w-full md:w-1/2 {{ $loop->even ? 'md:pl-12' : 'md:pr-12' }}">
                        <div class="bg-white border border-slate-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="bg-gradient-to-r from-blue-900 to-blue-800 px-6 py-5 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white/10 flex items-center justify-center shrink-0 text-white text-lg">
                                        <i class="fa-solid fa-user-tie"></i>
                                    </div>
                                    <div>
                                        <span class="text-amber-400 text-xs font-bold tracking-widest block">{{ $period['period'] }}</span>
                                        <h3 class="text-white font-bold text-base leading-tight mt-0.5">{{ $period['name'] }}</h3>
                                    </div>
                                </div>
                                <span class="text-white/30 text-sm font-black">{{ str_pad($period['number'], 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="hidden md:flex absolute left-1/2 top-6 -translate-x-1/2 z-10">
                        <div class="w-5 h-5 rounded-full border-4 border-white shadow-md bg-blue-900"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
