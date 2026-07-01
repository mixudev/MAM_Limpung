@extends('layouts.app')

@section('seo_title', 'Struktur Organisasi — MAM Limpung')
@section('seo_description', 'Susunan organisasi dan kepengurusan MAM Limpung, mulai dari kepala madrasah, wakil kepala, tata usaha, hingga dewan guru.')

@section('content')
<div class="bg-white pt-12 pb-0 font-sans">
    {{-- Hero --}}
    <div class="relative py-20 md:py-32 overflow-hidden">
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-indigo-50/50 rounded-full blur-[100px] -translate-y-1/2 -translate-x-1/4"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span class="text-[10px] tracking-[0.4em] text-blue-900 font-bold uppercase mb-6 block" data-aos="fade-down">Struktur Organisasi</span>
            <h1 class="text-4xl md:text-6xl font-black text-gray-900 leading-tight mb-6" data-aos="fade-up">
                Struktur <span class="text-amber-500">Organisasi</span>
            </h1>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                Bagan susunan kepengurusan MAM Limpung yang solid dan terstruktur untuk mewujudkan visi dan misi madrasah.
            </p>
        </div>
    </div>

    {{-- Gambar Struktur Organisasi --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 mb-16" data-aos="fade-up">
        <div class="bg-[#f4f6f8] border border-slate-200 p-4 md:p-8">
            <a href="{{ asset('images/struktur-organisasi.png') }}" target="_blank" class="block">
                <img src="{{ asset('images/struktur-organisasi.png') }}" alt="Struktur Organisasi MAM Limpung" class="w-full h-auto shadow-lg">
            </a>
            <p class="text-center text-xs text-slate-400 mt-3 font-medium">Klik gambar untuk memperbesar</p>
        </div>
    </div>

    {{-- Rincian Struktur Organisasi --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="text-center mb-14">
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter mb-2">Rincian Kepengurusan</h2>
            <div class="w-16 h-1 bg-blue-900 mx-auto"></div>
            <p class="text-slate-500 mt-4 max-w-2xl mx-auto text-sm">Detail nama-nama pengurus madrasah berdasarkan bidang dan fungsinya.</p>
        </div>

        {{-- Komite Madrasah --}}
        @if($komite)
        <div class="flex justify-center mb-10" data-aos="fade-down">
            <div class="bg-emerald-700 text-white border-b-4 border-emerald-400 px-8 py-5 text-center min-w-[260px] shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-12 h-12 bg-white/10 flex items-center justify-center mx-auto mb-2 text-xl">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <h3 class="text-base font-bold">{{ $komite->nama }}</h3>
                <p class="text-emerald-200 text-[10px] font-semibold tracking-wider uppercase mt-1">{{ $komite->categories->pluck('name')->join(' & ') }}</p>
            </div>
        </div>
        @endif

        {{-- Connecting Line --}}
        <div class="hidden md:block w-0.5 h-6 bg-slate-300 mx-auto mb-8"></div>

        {{-- Kepala Madrasah --}}
        @if($kepala)
        <div class="flex justify-center mb-14" data-aos="fade-down">
            <div class="bg-blue-900 text-white border-b-4 border-amber-500 px-10 py-6 text-center min-w-[300px] shadow-xl hover:shadow-2xl transition-shadow">
                <div class="w-16 h-16 bg-white/10 flex items-center justify-center mx-auto mb-3 text-2xl">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <h3 class="text-lg font-bold">{{ $kepala->nama }}</h3>
                <p class="text-blue-200 text-xs font-semibold tracking-wider uppercase mt-1">{{ $kepala->categories->pluck('name')->join(' & ') }}</p>
            </div>
        </div>
        @endif

        {{-- Connecting Line --}}
        <div class="hidden md:block w-0.5 h-8 bg-slate-300 mx-auto mb-8"></div>

        {{-- Wakil Kepala Madrasah --}}
        @if($wakil->isNotEmpty())
        <div class="mb-12">
            <div class="hidden md:flex justify-center items-center mb-6">
                <span class="text-[10px] tracking-[0.4em] text-slate-400 font-bold uppercase bg-slate-50 px-4 py-1 border border-slate-200">Wakil Kepala Madrasah</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" data-aos="fade-up">
                @foreach ($wakil as $w)
                <div class="bg-white border border-slate-200 border-t-4 border-t-blue-900 p-5 text-center hover:shadow-lg transition-shadow duration-300 group">
                    <div class="w-12 h-12 bg-blue-50 text-blue-900 flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-900 group-hover:text-white transition-all text-lg">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">{{ $w->nama }}</h4>
                    <p class="text-[11px] text-slate-500 font-semibold mt-1">{{ $w->categories->pluck('name')->join(' & ') }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Connecting Line --}}
        <div class="hidden md:block w-0.5 h-8 bg-slate-300 mx-auto mb-8"></div>

        {{-- Tata Usaha & Bendahara & Operator --}}
        @if($tataUsaha->isNotEmpty() || $bendahara->isNotEmpty() || $operator)
        <div class="mb-12">
            <div class="hidden md:flex justify-center items-center mb-6">
                <span class="text-[10px] tracking-[0.4em] text-slate-400 font-bold uppercase bg-slate-50 px-4 py-1 border border-slate-200">Tata Usaha & Bendahara</span>
            </div>
            <div class="space-y-4" data-aos="fade-up">
                @if($tataUsaha->isNotEmpty())
                <div class="bg-white border border-slate-200 p-5">
                    <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Kepala & Staf Tata Usaha</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($tataUsaha as $tu)
                        <div class="flex items-center gap-3 px-3 py-2 bg-slate-50 hover:bg-blue-50 transition-colors border-l-2 border-blue-200">
                            <div class="w-8 h-8 bg-blue-100 text-blue-700 flex items-center justify-center text-xs shrink-0">
                                <i class="fa-solid fa-folder-open"></i>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-slate-800">{{ $tu->nama }}</span>
                                <p class="text-[10px] text-slate-400 font-medium">{{ $tu->categories->pluck('name')->join(' & ') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($operator)
                <div class="bg-white border border-slate-200 p-5">
                    <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Operator Madrasah</h4>
                    <div class="flex items-center gap-3 px-3 py-2 bg-slate-50 hover:bg-blue-50 transition-colors border-l-2 border-blue-200 max-w-md">
                        <div class="w-8 h-8 bg-sky-100 text-sky-700 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-desktop"></i>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-slate-800">{{ $operator->nama }}</span>
                            <p class="text-[10px] text-slate-400 font-medium">{{ $operator->categories->pluck('name')->join(' & ') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($bendahara->isNotEmpty())
                <div class="bg-white border border-slate-200 p-5">
                    <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Bendahara Madrasah</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($bendahara as $b)
                        <div class="flex items-center gap-3 px-3 py-2 bg-slate-50 hover:bg-emerald-50 transition-colors border-l-2 border-emerald-400">
                            <div class="w-8 h-8 bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs shrink-0">
                                <i class="fa-solid fa-coins"></i>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-slate-800">{{ $b->nama }}</span>
                                <p class="text-[10px] text-slate-400 font-medium">{{ $b->categories->pluck('name')->join(' & ') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Connecting Line --}}
        <div class="hidden md:block w-0.5 h-8 bg-slate-300 mx-auto mb-8"></div>
        @endif

        {{-- Unit Pendukung --}}
        @if($unit->isNotEmpty())
        <div class="mb-12">
            <div class="hidden md:flex justify-center items-center mb-6">
                <span class="text-[10px] tracking-[0.4em] text-slate-400 font-bold uppercase bg-slate-50 px-4 py-1 border border-slate-200">Unit Pendukung</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" data-aos="fade-up" data-aos-delay="100">
                @foreach ($unit as $u)
                <div class="bg-white border border-slate-200 p-4 flex items-center gap-4 hover:shadow-md transition-shadow duration-300 group">
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 group-hover:bg-amber-500 group-hover:text-white transition-all text-sm">
                        <i class="fa-solid fa-flask"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">{{ $u->nama }}</h4>
                        <p class="text-[11px] text-slate-500 font-medium">{{ $u->categories->pluck('name')->join(' & ') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Connecting Line --}}
        <div class="hidden md:block w-0.5 h-8 bg-slate-300 mx-auto mb-8"></div>
        @endif

        {{-- Wali Kelas --}}
        @if($waliKelas->isNotEmpty())
        <div class="mb-12">
            <div class="hidden md:flex justify-center items-center mb-6">
                <span class="text-[10px] tracking-[0.4em] text-slate-400 font-bold uppercase bg-slate-50 px-4 py-1 border border-slate-200">Wali Kelas</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" data-aos="fade-up" data-aos-delay="100">
                @foreach ($waliKelas as $wk)
                <div class="bg-white border border-slate-200 border-t-4 border-t-indigo-500 p-5 text-center hover:shadow-lg transition-shadow duration-300 group">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 group-hover:bg-indigo-600 group-hover:text-white transition-all text-lg">
                        <i class="fa-solid fa-people-group"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">{{ $wk->nama }}</h4>
                    <p class="text-[11px] text-slate-500 font-semibold mt-1">{{ $wk->categories->pluck('name')->join(' & ') }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Connecting Line --}}
        <div class="hidden md:block w-0.5 h-8 bg-slate-300 mx-auto mb-8"></div>
        @endif

        {{-- Guru BK --}}
        @if($guruBk->isNotEmpty())
        <div class="mb-12">
            <div class="hidden md:flex justify-center items-center mb-6">
                <span class="text-[10px] tracking-[0.4em] text-slate-400 font-bold uppercase bg-slate-50 px-4 py-1 border border-slate-200">Guru Bimbingan Konseling</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" data-aos="fade-up" data-aos-delay="100">
                @foreach ($guruBk as $bk)
                <div class="bg-white border border-slate-200 border-t-4 border-t-pink-500 p-5 text-center hover:shadow-lg transition-shadow duration-300 group">
                    <div class="w-12 h-12 bg-pink-50 text-pink-600 flex items-center justify-center mx-auto mb-3 group-hover:bg-pink-600 group-hover:text-white transition-all text-lg">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">{{ $bk->nama }}</h4>
                    <p class="text-[11px] text-slate-500 font-semibold mt-1">{{ $bk->categories->pluck('name')->join(' & ') }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Connecting Line --}}
        <div class="hidden md:block w-0.5 h-8 bg-slate-300 mx-auto mb-8"></div>
        @endif

        {{-- Dewan Guru --}}
        @if($guru->isNotEmpty())
        <div>
            <div class="hidden md:flex justify-center items-center mb-6">
                <span class="text-[10px] tracking-[0.4em] text-slate-400 font-bold uppercase bg-slate-50 px-4 py-1 border border-slate-200">Dewan Guru</span>
            </div>
            <div class="bg-[#f4f6f8] border border-slate-200 p-6 md:p-8" data-aos="fade-up" data-aos-delay="150">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($guru as $g)
                    <div class="flex items-center gap-3 bg-white border border-slate-100 px-4 py-3 hover:border-blue-200 hover:shadow-sm transition-all">
                        <div class="w-8 h-8 bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-bold rounded-full shrink-0">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-slate-800">{{ $g->nama }}</span>
                            <p class="text-[10px] text-slate-400 font-medium">{{ $g->categories->pluck('name')->join(' & ') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
