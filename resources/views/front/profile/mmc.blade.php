@extends('layouts.app')

@section('seo_title', 'Mualim Master Class (MMC) — MAM Limpung')
@section('seo_description', 'Program ekstrakurikuler Mualim Master Class MAM Limpung yang mewadahi dan melejitkan potensi siswa melalui berbagai kegiatan berbasis minat, bakat, dan penguatan kompetensi.')

@section('content')
<div class="bg-[#0a0f1a] pb-0 font-sans">
    {{-- Hero Premium --}}
    <div class="relative pt-12 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-blue-950/60 via-transparent to-[#0a0f1a] z-10"></div>
        <div class="absolute top-20 md:top-32 -left-20 w-[400px] h-[400px] bg-amber-500/5 rounded-full blur-[120px]"></div>
        <div class="absolute top-40 md:top-52 -right-20 w-[300px] h-[300px] bg-blue-500/5 rounded-full blur-[100px]"></div>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 py-20 md:py-32">
            <div class="text-center" data-aos="fade-down">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 border border-amber-500/30 bg-amber-500/5 text-amber-400 text-[10px] font-bold tracking-[0.3em] uppercase mb-8">
                    <i class="fa-solid fa-crown text-xs"></i>
                    Ekstrakurikuler Unggulan
                </div>
                <h1 class="text-4xl md:text-7xl font-black text-white leading-tight mb-4">
                    Mualim <br class="md:hidden">
                    <span class="text-amber-400">Master Class</span>
                </h1>
                <div class="w-16 h-1 bg-amber-500 mx-auto mb-6"></div>
                <p class="text-blue-200/80 text-base md:text-lg max-w-3xl mx-auto leading-relaxed font-medium">
                    Program ekstrakurikuler yang dirancang sebagai wadah pengembangan minat, bakat, 
                    dan penguatan kompetensi siswa melalui pengalaman praktik, eksplorasi, serta pembinaan intensif sesuai bidang yang diminati.
                </p>
            </div>
        </div>
    </div>

    {{-- Ekskul Grid --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 -mt-10">
        <div class="text-center mb-14">
            <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter mb-2">Program MMC</h2>
            <div class="w-16 h-1 bg-amber-500 mx-auto"></div>
            <p class="text-white/40 mt-4 max-w-2xl mx-auto text-sm">Sembilan program ekstrakurikuler unggulan yang membentuk potensi siswa secara holistik.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($ekskuls as $index => $ekskul)
            <div class="group bg-white/5 backdrop-blur border border-white/10 p-8 hover:bg-white/10 transition-all duration-300 relative overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $index * 60 }}">
                <div class="absolute top-0 right-0 w-32 h-32 opacity-0 group-hover:opacity-10 transition-opacity duration-500"
                     style="background-color: {{ match($ekskul['color']) {
                         'emerald' => '#10b981',
                         'red' => '#ef4444',
                         'blue' => '#3b82f6',
                         'purple' => '#8b5cf6',
                         'amber' => '#f59e0b',
                         'sky' => '#0ea5e9',
                         'indigo' => '#6366f1',
                         'pink' => '#ec4899',
                         default => '#6b7280',
                     } }}; border-radius: 0 0 0 100%;"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 flex items-center justify-center text-white text-xl mb-5 shadow-lg transition-all duration-300 group-hover:scale-110
                        {{ match($ekskul['color']) {
                            'emerald' => 'bg-emerald-500',
                            'red' => 'bg-red-500',
                            'blue' => 'bg-blue-600',
                            'purple' => 'bg-purple-600',
                            'amber' => 'bg-amber-500',
                            'sky' => 'bg-sky-500',
                            'indigo' => 'bg-indigo-600',
                            'pink' => 'bg-pink-500',
                            default => 'bg-slate-500',
                        } }}">
                        <i class="{{ $ekskul['icon'] }}"></i>
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">{{ $ekskul['title'] }}</h3>
                    <p class="text-white/50 text-sm leading-relaxed">{{ $ekskul['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- CTA --}}
    <div class="border-t border-white/10 py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="zoom-in">
            <h2 class="text-2xl md:text-3xl font-black text-white mb-4">Kembangkan Potensimu Bersama MMC</h2>
            <p class="text-white/50 text-sm mb-8 max-w-xl mx-auto">Temukan dan kembangkan bakat terbaikmu melalui program ekstrakurikuler Mualim Master Class MAM Limpung.</p>
            <a href="{{ route('frontend.ppdb.index') }}" class="inline-flex items-center gap-2 bg-amber-500 text-blue-950 px-8 py-4 text-sm font-black uppercase tracking-wider hover:bg-amber-400 transition-colors shadow-xl shadow-amber-500/20">
                Daftar Sekarang
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection
