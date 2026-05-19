@extends('layouts.app')

@section('content')
@php
    $today = date('Y-m-d');
    $isFuture = $today < $general['start_date'];
    $isPast = $today > $general['end_date'];
@endphp

<section class="min-h-[75vh] flex items-center justify-center px-6 py-12 sm:py-16 bg-slate-50">
    <div class="max-w-xl w-full text-center">
        
        <!-- Decisive Minimalist Icon -->
        <div class="mb-8 inline-flex items-center justify-center">
            <div class="w-16 h-16 bg-white border border-slate-200 shadow-sm flex items-center justify-center">
                @if($isFuture)
                    <!-- Minimalist Timer Icon -->
                    <svg class="w-8 h-8 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @elseif($isPast)
                    <!-- Minimalist Lock Icon -->
                    <svg class="w-8 h-8 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                @else
                    <!-- Minimalist Tool Icon -->
                    <svg class="w-8 h-8 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                @endif
            </div>
        </div>

        <!-- Strict Professional Headline -->
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight sm:text-3xl uppercase font-sans">
            @if($isFuture)
                Pendaftaran Belum Dibuka
            @elseif($isPast)
                Masa Pendaftaran Online Telah Berakhir
            @else
                Pendaftaran Dinonaktifkan Sementara
            @endif
        </h1>

        <!-- Easy to Understand Explanation -->
        <div class="text-slate-600 text-sm leading-relaxed mt-4 mb-8 max-w-md mx-auto">
            @if($isFuture)
                <p>
                    Halo calon peserta didik baru. Gerbang pendaftaran online Tahun Pelajaran 
                    <strong>{{ $general['tahun_ajaran'] }}/{{ $general['tahun_ajaran'] + 1 }}</strong> 
                    belum resmi dibuka. Silakan persiapkan berkas persyaratan administrasi Anda terlebih dahulu sebelum jadwal pelaksanaan dimulai.
                </p>
            @elseif($isPast)
                <p class="mb-4">
                    Masa registrasi pendaftaran online gelombang ini sudah ditutup resmi. Namun, bagi Anda lulusan SMP/MTs yang masih ingin bergabung dengan MAM Limpung, Anda tetap dapat menghubungi panitia kami.
                </p>
                <div class="text-xs text-left bg-slate-100 border-l-2 border-emerald-800 p-4 text-slate-700">
                    <strong>Peluang Jalur Offline:</strong> Silakan hubungi nomor WhatsApp sekretariat di bawah untuk menanyakan ketersediaan kuota cadangan atau mendaftar langsung di kampus sekolah.
                </div>
            @else
                <p>
                    Registrasi online sedang ditutup sementara oleh panitia untuk pembaharuan data pendaftar dan penyesuaian kuota kelas. Laman formulir akan aktif kembali dalam waktu dekat.
                </p>
            @endif
        </div>

        <!-- Simple Tegas Info Box -->
        <div class="bg-white border border-slate-200 p-5 mb-8 text-left rounded-none">
            <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3.5 font-mono">Jadwal PPDB Online</h3>
            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-slate-400 block uppercase font-mono text-[9px]">Tanggal Mulai</span>
                    <span class="font-bold text-slate-800">
                        {{ \Carbon\Carbon::parse($general['start_date'])->translatedFormat('d F Y') }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block uppercase font-mono text-[9px]">Tanggal Selesai</span>
                    <span class="font-bold text-slate-800">
                        {{ \Carbon\Carbon::parse($general['end_date'])->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Professional Accent Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 items-center justify-center">
            @if($isPast)
                <a href="https://wa.me/6281234567890?text=Halo%20Panitia%20PPDB%20MAM%20Limpung,%20apakah%20masih%20ada%20kuota%20kelas%20offline?" 
                   target="_blank" 
                   class="w-full sm:w-auto px-6 py-3 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs uppercase tracking-wider transition-colors shadow-sm flex items-center justify-center gap-2">
                    Hubungi Sekretariat (WA)
                </a>
            @else
                <a href="{{ route('frontend.home') }}" class="w-full sm:w-auto px-6 py-3 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider hover:bg-black transition-colors shadow-sm">
                    Kembali ke Beranda
                </a>
            @endif
            <a href="{{ route('frontend.ppdb.status') }}" class="w-full sm:w-auto px-6 py-3 bg-white border border-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-colors text-center">
                Cek Status Pendaftaran
            </a>
        </div>

        <!-- Strict School Theme Contact Footer -->
        <div class="mt-12 pt-6 border-t border-slate-200/80 text-center">
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider font-mono">Sekretariat Panitia PPDB MAM Limpung</p>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto font-sans">
                Gedung Utama Lantai 1, Jl. Cokronegoro No.34, Limpung, Kab. Batang. Jam Layanan: 08.00 - 13.00 WIB.
            </p>
        </div>
    </div>
</section>
@endsection
