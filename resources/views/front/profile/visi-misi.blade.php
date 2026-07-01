@extends('layouts.app')

@section('seo_title', 'Visi dan Misi — MAM Limpung')
@section('seo_description', 'Visi dan Misi MAM Limpung dalam mencetak generasi unggul yang berakhlak mulia, kreatif dalam karya, dan unggul dalam ilmu pengetahuan.')

@section('content')
<div class="bg-white pt-12 pb-0 font-sans">
    {{-- Hero --}}
    <div class="relative py-20 md:py-32 overflow-hidden">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-50/50 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-amber-50/50 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/4"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span class="text-[10px] tracking-[0.4em] text-blue-900 font-bold uppercase mb-6 block" data-aos="fade-down">Arah & Tujuan</span>
            <h1 class="text-4xl md:text-6xl font-black text-gray-900 leading-tight mb-6" data-aos="fade-up">
                Visi & <span class="text-amber-500">Misi</span>
            </h1>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                Landasan filosofis yang menjadi kompas dan pedoman dalam setiap langkah pendidikan di MAM Limpung.
            </p>
        </div>
    </div>

    {{-- Branding Badge --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 mb-10 text-center" data-aos="fade-down">
        <div class="inline-flex items-center gap-2 bg-amber-50 border border-amber-200 px-5 py-2.5">
            <i class="fa-solid fa-store text-amber-600"></i>
            <span class="text-sm font-bold text-amber-800 uppercase tracking-wider">{{ $branding }}</span>
        </div>
    </div>

    {{-- Motto --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 text-center" data-aos="fade-up">
        <p class="text-base text-slate-500 italic font-serif">
            "{{ $motto }}"
        </p>
    </div>

    {{-- Vision Card --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
        <div class="bg-blue-900 text-white p-10 md:p-14 shadow-2xl relative overflow-hidden" data-aos="zoom-in">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-amber-500/10 rounded-full translate-y-1/2 -translate-x-1/4"></div>
            <div class="relative z-10 text-center">
                <div class="w-12 h-1 bg-amber-500 mx-auto mb-6"></div>
                <span class="text-[10px] tracking-[0.4em] text-amber-400 font-bold uppercase mb-4 block">Visi Kami</span>
                <p class="text-2xl md:text-3xl font-bold leading-relaxed italic max-w-4xl mx-auto">
                    "{{ $vision }}"
                </p>
            </div>
        </div>
    </div>

    {{-- Missions --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="text-center mb-14">
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter mb-2">Misi Kami</h2>
            <div class="w-16 h-1 bg-amber-500 mx-auto"></div>
            <p class="text-slate-500 mt-4 max-w-2xl mx-auto text-sm">Tujuh pilar utama yang menjadi komitmen kami dalam mewujudkan visi madrasah.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($missions as $index => $mission)
            <div class="group bg-white border border-slate-200 p-8 hover:shadow-xl transition-all duration-300 relative overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">
                <div class="absolute top-0 right-0 w-24 h-24 rounded-bl-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                     style="background-color: {{ match($mission['color']) {
                         'bg-emerald-500' => '#ecfdf5',
                         'bg-amber-500' => '#fffbeb',
                         'bg-blue-600' => '#eff6ff',
                         'bg-teal-600' => '#ecfdf5',
                         'bg-indigo-600' => '#eef2ff',
                         'bg-amber-600' => '#fffbeb',
                         'bg-sky-600' => '#f0f9ff',
                         default => '#f8fafc'
                     } }}"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 flex items-center justify-center text-white text-xl shadow-md transition-all duration-300 group-hover:scale-110 {{ $mission['color'] }}">
                            <i class="{{ $mission['icon'] }}"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-300 bg-slate-50 px-3 py-1 border border-slate-100">Misi {{ $mission['number'] }}</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-3 leading-tight">{{ $mission['title'] }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">{{ $mission['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
