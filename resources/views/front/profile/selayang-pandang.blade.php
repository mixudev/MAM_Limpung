@extends('layouts.app')

@section('seo_title', 'Selayang Pandang — MAM Limpung')
@section('seo_description', 'Simak perjalanan sejarah MAM Limpung dari masa ke masa, mulai dari pendirian 11 April 1985 hingga menjadi madrasah unggulan di Kabupaten Batang.')

@section('content')
<div class="bg-[#fcfbf9] pt-12 pb-0 font-sans">
    {{-- Hero --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="relative bg-slate-900 overflow-hidden border border-slate-800 p-10 md:p-16 flex flex-col md:flex-row items-center justify-between">
            <img src="{{ asset('assets/img/school.png') }}" alt="Sejarah MAM Limpung" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/95 to-slate-900/80 z-10"></div>
            <div class="relative z-20 md:w-2/3">
                <div class="w-12 h-1.5 bg-amber-500 mb-6 shadow-lg shadow-amber-500/20"></div>
                <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter leading-tight mb-4 drop-shadow-md">
                    Selayang <span class="text-amber-500">Pandang</span>
                </h1>
                <p class="text-blue-100 text-base md:text-lg font-medium leading-relaxed">
                    Perjalanan panjang MAM Limpung dalam mencerdaskan kehidupan bangsa, berlandaskan nilai-nilai Islami dan semangat Muhammadiyah sejak {{ $establishDate }}.
                </p>
            </div>
        </div>
    </div>

    {{-- Establishment Stats --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 mb-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-stone-200 p-6 text-center shadow-sm" data-aos="fade-up">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
                <span class="text-2xl font-black text-slate-900 block">{{ $establishDate }}</span>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mt-1">Tanggal Berdiri</p>
            </div>
            <div class="bg-white border border-stone-200 p-6 text-center shadow-sm" data-aos="fade-up" data-aos-delay="50">
                <div class="w-12 h-12 bg-blue-50 text-blue-700 flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                <span class="text-sm font-bold text-slate-900 block leading-tight">{{ $charterNumber }}</span>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mt-1">Piagam Madrasah</p>
            </div>
            <div class="bg-white border border-stone-200 p-6 text-center shadow-sm" data-aos="fade-up" data-aos-delay="100">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="fa-solid fa-quote-left"></i>
                </div>
                <span class="text-sm font-bold text-slate-900 block leading-tight">{{ $motto }}</span>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mt-1">Motto</p>
            </div>
        </div>
    </div>

    {{-- Storytelling --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="text-center mb-14">
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter mb-2">Cerita Kami</h2>
            <div class="w-16 h-1 bg-blue-900 mx-auto"></div>
            <p class="text-slate-500 mt-4 max-w-2xl mx-auto text-sm">Setiap langkah adalah sejarah. Berikut kisah perjalanan MAM Limpung.</p>
        </div>

        <div class="space-y-10">
            @foreach ($paragraphs as $index => $item)
            <div class="flex gap-6 md:gap-8" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">
                <div class="hidden md:flex flex-col items-center">
                    <div class="w-14 h-14 flex items-center justify-center text-white text-lg shadow-md
                        {{ $index < count($paragraphs) - 1 ? 'bg-blue-900' : 'bg-amber-500' }}">
                        <i class="{{ $item['icon'] }}"></i>
                    </div>
                    @if (!$loop->last)
                    <div class="w-0.5 flex-1 bg-stone-200 mt-2"></div>
                    @endif
                </div>
                <div class="flex-1 bg-white border border-stone-200 p-6 md:p-8 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="md:hidden w-10 h-10 flex items-center justify-center text-white text-sm shadow-md
                            {{ $index < count($paragraphs) - 1 ? 'bg-blue-900' : 'bg-amber-500' }}">
                            <i class="{{ $item['icon'] }}"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">{{ $item['title'] }}</h3>
                    </div>
                    <p class="text-slate-700 text-sm md:text-base leading-relaxed text-justify">{{ $item['content'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Tagline Quote --}}
        <div class="mt-16 bg-blue-900 text-white p-10 md:p-14 text-center relative overflow-hidden" data-aos="zoom-in">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-amber-500/10 rounded-full translate-y-1/2 -translate-x-1/4"></div>
            <div class="relative z-10">
                <i class="fa-solid fa-quote-left text-3xl text-amber-400 mb-4 block"></i>
                <p class="text-2xl md:text-3xl font-bold italic leading-relaxed">
                    "{{ $tagline }}"
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
