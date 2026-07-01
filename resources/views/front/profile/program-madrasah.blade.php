@extends('layouts.app')

@section('seo_title', 'Program Madrasah — MAM Limpung')
@section('seo_description', 'Jelajahi program-program unggulan MAM Limpung yang dirancang untuk mengembangkan potensi akademik, keagamaan, dan keterampilan siswa.')

@section('content')
<div class="bg-white pt-12 pb-0 font-sans">
    {{-- Hero --}}
    <div class="relative py-20 md:py-32 overflow-hidden">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-50/50 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-amber-50/50 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/4"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span class="text-[10px] tracking-[0.4em] text-blue-900 font-bold uppercase mb-6 block" data-aos="fade-down">Program Unggulan</span>
            <h1 class="text-4xl md:text-6xl font-black text-gray-900 leading-tight mb-6" data-aos="fade-up">
                Program <span class="text-amber-500">Madrasah</span>
            </h1>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                Beragam program unggulan yang dirancang khusus untuk mengasah potensi, bakat, dan minat siswa MAM Limpung.
            </p>
        </div>
    </div>

    {{-- Programs Grid --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($programs as $index => $program)
            <div class="group bg-white border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $index * 60 }}">
                <div class="h-2 w-full {{ match($program['color']) {
                    'emerald' => 'bg-emerald-500',
                    'purple' => 'bg-purple-600',
                    'blue' => 'bg-blue-600',
                    'sky' => 'bg-sky-500',
                    'indigo' => 'bg-indigo-600',
                    'amber' => 'bg-amber-500',
                    'red' => 'bg-red-500',
                    'teal' => 'bg-teal-500',
                    'cyan' => 'bg-cyan-500',
                    'slate' => 'bg-slate-500',
                    'violet' => 'bg-violet-600',
                    default => 'bg-slate-500',
                } }}"></div>
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-14 h-14 flex items-center justify-center text-white text-xl shadow-md {{ match($program['color']) {
                            'emerald' => 'bg-emerald-500',
                            'purple' => 'bg-purple-600',
                            'blue' => 'bg-blue-600',
                            'sky' => 'bg-sky-500',
                            'indigo' => 'bg-indigo-600',
                            'amber' => 'bg-amber-500',
                            'red' => 'bg-red-500',
                            'teal' => 'bg-teal-500',
                            'cyan' => 'bg-cyan-500',
                            'slate' => 'bg-slate-500',
                            'violet' => 'bg-violet-600',
                            default => 'bg-slate-500',
                        } }}">
                            <i class="{{ $program['icon'] }}"></i>
                        </div>
                        <span class="text-[10px] font-bold tracking-widest uppercase text-slate-400 bg-slate-50 px-3 py-1 border border-slate-100">{{ $program['category'] }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-3 leading-tight">{{ $program['title'] }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">{{ $program['description'] }}</p>
                    <ul class="space-y-2.5">
                        @foreach ($program['items'] as $item)
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-check-circle text-emerald-500 text-xs mt-1 shrink-0"></i>
                            <span class="text-xs text-slate-600 font-medium leading-relaxed">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
