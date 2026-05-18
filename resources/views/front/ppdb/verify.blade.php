@extends('layouts.app')

@section('content')
<div class="min-h-screen py-16 px-4 bg-slate-50 flex flex-col items-center justify-center relative overflow-hidden">
    
    <!-- Ambient backdrops -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-500/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-xl relative z-10 animate-fade-in-up">
        
        <!-- Verification Portal Card -->
        <div class="bg-white rounded-none border border-slate-200 shadow-xl overflow-hidden p-8 text-center relative">
            
            <!-- Security Shield Pattern Watermark -->
            <div class="absolute inset-0 opacity-[0.02] pointer-events-none flex items-center justify-center">
                <i class="fa-solid fa-shield-halved text-[300px] text-emerald-600"></i>
            </div>

            <!-- Verification Status Header -->
            <div class="flex flex-col items-center justify-center mb-6 relative z-10">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 mb-3 shadow-inner">
                    <i class="fa-solid fa-shield-check text-3xl"></i>
                </div>
                <h1 class="text-lg font-black text-emerald-600 tracking-wider uppercase">Dokumen Terverifikasi Asli</h1>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Sistem Verifikasi TTE MAM Limpung</p>
            </div>

            <!-- Success Alert Text -->
            <div class="bg-emerald-50/50 border border-emerald-100 p-4 rounded-sm mb-6 text-xs text-slate-700 leading-relaxed text-left relative z-10">
                Sistem Tanda Tangan Elektronik (TTE) MAS Muhammadiyah Limpung menyatakan bahwa berkas bukti pendaftaran fisik dengan nomor registrasi di bawah ini adalah <strong>sah, asli,</strong> dan terdaftar dalam basis data PPDB resmi kami.
            </div>

            <!-- Student Data Layout (Split Photo & Details) -->
            <div class="flex flex-col sm:flex-row gap-5 items-start text-left pb-6 border-b border-slate-100 mb-6 relative z-10">
                
                <!-- Student Photo Frame -->
                <div class="flex flex-col items-center justify-center flex-shrink-0 mx-auto sm:mx-0">
                    <div class="relative w-20 h-28 border border-slate-200 bg-slate-50 p-1 flex items-center justify-center shadow-inner overflow-hidden">
                        <img src="{{ $ppdb_siswa->fotoUrl() }}" class="w-full h-full object-cover" alt="Foto Siswa">
                    </div>
                </div>

                <!-- Detailed Verification Data Table -->
                <div class="flex-1 w-full space-y-3">
                    <h3 class="text-[10px] font-bold uppercase tracking-wider text-blue-900 border-b border-slate-100 pb-0.5">Detail Validasi Data</h3>
                    
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs text-slate-700">
                        <div>
                            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">No. Registrasi</span>
                            <span class="font-mono font-black text-blue-950 uppercase">{{ $ppdb_siswa->nomor_registrasi }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Nama Lengkap</span>
                            <span class="font-bold text-slate-800">{{ $ppdb_siswa->nama_lengkap }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">NISN</span>
                            <span class="font-mono font-medium">{{ $ppdb_siswa->nisn }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Asal Sekolah</span>
                            <span class="font-medium">{{ $ppdb_siswa->sekolah_asal }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Waktu Pendaftaran</span>
                            <span class="font-medium text-slate-500 font-mono">{{ $ppdb_siswa->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                        </div>
                        <div>
                            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Status Validasi</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-green-100 text-green-800 border border-green-200">
                                AKTIF / VALID
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Authentic Verification Stamp -->
            <div class="text-[9px] text-slate-400 flex justify-between items-center relative z-10">
                <span>Divalidasi pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
                <span class="font-mono text-emerald-600 font-semibold tracking-wider">SECURE GENUINE ID</span>
            </div>
        </div>

        <!-- Back home option -->
        <div class="mt-6 text-center">
            <a href="{{ route('frontend.home') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 hover:text-slate-800 transition-colors">
                <i class="fa-solid fa-house mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
