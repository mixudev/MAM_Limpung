@extends('layouts.app')

@section('content')
<!-- Import Plus Jakarta Sans for premium typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;850&display=swap" rel="stylesheet">

<div class="min-h-screen py-8 px-4 bg-slate-50 flex flex-col items-center justify-center relative overflow-hidden print:bg-white print:p-0 print:min-h-0" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    
    <!-- Ambient backdrops -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/5 rounded-full blur-[100px] pointer-events-none print:hidden"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-[100px] pointer-events-none print:hidden"></div>

    <!-- Main Container -->
    <div class="w-full max-w-3xl relative z-10 animate-fade-in-up print:max-w-full print:shadow-none print:m-0">
        
        <!-- Action Buttons Top (Print & Home) -->
        <div class="flex justify-between items-center mb-4 print:hidden">
            <a href="{{ route('frontend.home') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 hover:text-slate-800 transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>
            
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-blue-900 text-white text-xs font-bold uppercase tracking-wider rounded-sm shadow-md hover:bg-black transition-colors">
                <i class="fa-solid fa-print mr-2"></i> Cetak Dokumen
            </button>
        </div>

        <!-- Success Email Notification Banner -->
        <div class="mb-4 bg-emerald-50 border-l-4 border-emerald-500 p-3 rounded-sm shadow-sm flex items-start space-x-3 print:hidden">
            <div class="flex-shrink-0 text-emerald-500 mt-0.5">
                <i class="fa-solid fa-circle-check text-base"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800 leading-tight">Pendaftaran Berhasil Terkirim</h4>
                <p class="text-[11px] text-slate-600 mt-1 leading-relaxed">
                    Detail pendaftaran dan bukti registrasi telah berhasil dikirimkan ke email Anda: 
                    <span class="font-semibold text-blue-900">{{ $ppdb_siswa->email }}</span>. 
                    Silakan periksa kotak masuk atau folder spam Anda.
                </p>
            </div>
        </div>

        <!-- The Formal Document Wrapper -->
        <div id="printArea" class="bg-white rounded-none border border-slate-200 shadow-md p-6 relative flex flex-col justify-between print:border-none print:shadow-none print:p-0 print:m-0 print:w-full print:max-w-full print:page-break-inside-avoid print:min-h-[258mm]">
            
            <!-- Top Group Wrapper to separate signature to the bottom -->
            <div class="flex-grow flex flex-col justify-start">
                
                <!-- FORMAL KOP SURAT (OFFICIAL HEADER) -->
                <div class="flex items-center justify-start pb-2.5  print:border-slate-900">
                    <div class="flex items-center space-x-4 text-left">
                        <img src="{{ asset('assets/img/logo.png') }}" class="w-12 h-12 object-contain print:w-14 print:h-14" alt="Logo Sekolah">
                        <div>
                            {{-- <p class="text-[9px] font-bold text-slate-700 tracking-wide leading-none uppercase print:text-black">
                                </p> --}}
                            <h1 class="text-sm font-black text-blue-900 tracking-tight leading-tight uppercase mt-0.5 print:text-black">
                                MA MUHAMMADIYAH LIMPUNG</h1>
                            <p class="text-[8px] font-bold text-slate-600 tracking-wider leading-none uppercase mt-0.5 print:text-slate-800">TERAKREDITASI A (UNGGUL) | NPSN: 20362973</p>
                            <p class="text-[8px] text-slate-500 leading-none mt-1 print:text-slate-700 font-medium">
                                Jl. Raya Limpung - Banyuputih Km. 1, Limpung, Batang 51271 | Telp: (0285) 446877 | www.mamlimpung.sch.id
                            </p>
                        </div>
                    </div>
                </div>

            <!-- DOCUMENT TITLE -->
            <div class="text-center my-10">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider print:text-black">KARTU BUKTI PENDAFTARAN PPDB ONLINE</h2>
                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-wider print:text-slate-800">TAHUN AJARAN 2026/2027</p>
            </div>

            <!-- STUDENT DETAILS SECTION -->
            <div class="flex flex-col sm:flex-row gap-4 items-start pb-3 border-b border-slate-200">
                
                <!-- Formal Student Photo Block -->
                <div class="flex flex-col items-center justify-center flex-shrink-0 mx-auto sm:mx-0">
                    <div class="relative w-22 h-30  p-1 flex items-center justify-center shadow-inner overflow-hidden print:w-24 print:h-32 ">
                        <img src="{{ $ppdb_siswa->fotoUrl() }}" class="w-full h-full object-cover" alt="Foto Siswa">
                    </div>
                    <span class="text-[7px] text-slate-400 font-bold uppercase mt-1 tracking-wider print:text-black">PAS FOTO</span>
                </div>

                <!-- Student Details Data Grid -->
                <div class="flex-1 w-full space-y-1.5">
                    <h3 class="text-[10.5px] font-bold uppercase tracking-wider text-blue-900  pb-0.5 print:text-black print:border-black">Identitas Calon Siswa</h3>
                    
                    <div class="grid grid-cols-2 gap-x-5 gap-y-1 text-[11px] sm:text-[11.5px] text-slate-700 print:text-black">
                        <div>
                            <span class="block text-[8px] sm:text-[8.5px] font-bold text-slate-400 uppercase tracking-wider">No. Registrasi</span>
                            <span class="font-mono font-black text-[13px] sm:text-[14px] text-blue-950 uppercase print:text-black tracking-wide">{{ $ppdb_siswa->nomor_registrasi }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] sm:text-[8.5px] font-bold text-slate-400 uppercase tracking-wider">Nama Lengkap</span>
                            <span class="font-bold text-slate-900 print:text-black">{{ $ppdb_siswa->nama_lengkap }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] sm:text-[8.5px] font-bold text-slate-400 uppercase tracking-wider">NISN</span>
                            <span class="font-mono font-semibold text-slate-900 print:text-black">{{ $ppdb_siswa->nisn }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] sm:text-[8.5px] font-bold text-slate-400 uppercase tracking-wider">Jenis Kelamin</span>
                            <span class="font-semibold text-slate-900 print:text-black">{{ $ppdb_siswa->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] sm:text-[8.5px] font-bold text-slate-400 uppercase tracking-wider">Tempat, Tanggal Lahir</span>
                            <span class="font-semibold text-slate-900 print:text-black">{{ $ppdb_siswa->tempat_lahir }}, {{ $ppdb_siswa->tanggal_lahir->translatedFormat('d F Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] sm:text-[8.5px] font-bold text-slate-400 uppercase tracking-wider">Asal Sekolah</span>
                            <span class="font-semibold text-slate-900 print:text-black">{{ $ppdb_siswa->sekolah_asal }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] sm:text-[8.5px] font-bold text-slate-400 uppercase tracking-wider">Nomor HP / WA</span>
                            <span class="font-semibold text-slate-900 print:text-black font-mono">{{ $ppdb_siswa->nomor_hp }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] sm:text-[8.5px] font-bold text-slate-400 uppercase tracking-wider">Email Aktif</span>
                            <span class="font-semibold text-slate-900 print:text-black">{{ $ppdb_siswa->email }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] sm:text-[8.5px] font-bold text-slate-400 uppercase tracking-wider">Ukuran Baju</span>
                            <span class="font-semibold text-slate-900 print:text-black uppercase">{{ $ppdb_siswa->ukuran_baju }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] sm:text-[8.5px] font-bold text-slate-400 uppercase tracking-wider">Alamat Lengkap</span>
                            <span class="font-semibold text-slate-900 print:text-black leading-tight">{{ $ppdb_siswa->alamat_lengkap }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COMPRESSED RE-REGISTRATION INFO SECTION (2-COLUMN LAYOUT) -->
            <div class="my-3 bg-slate-50 border border-slate-200 p-3.5 rounded-sm print:bg-white print:border-slate-300 print:p-2.5">
                <h3 class="text-[9px] font-black uppercase tracking-wider text-blue-900  pb-0.5 mb-2 print:text-black print:border-black">
                    INFORMASI PENDAFTARAN & PERSYARATAN DAFTAR ULANG
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-[10px] text-slate-700 print:text-black print:gap-3">
                    
                    <!-- Left Column: Documents to Bring -->
                    <div class="space-y-1">
                        <h4 class="font-bold text-slate-800 border-b border-slate-200 pb-0.5 print:text-black">Berkas yang Wajib Dibawa:</h4>
                        <ul class="list-decimal list-inside space-y-0.5 text-[9.5px] leading-relaxed text-slate-600 print:text-black">
                            <li>Cetak Bukti Pendaftaran online resmi ini.</li>
                            <li>Fotokopi Ijazah/SKL dilegalisir (2 lembar).</li>
                            <li>Fotokopi Akta Kelahiran (2 lembar).</li>
                            <li>Fotokopi Kartu Keluarga (KK) (2 lembar).</li>
                            <li>Fotokopi KTP Ayah & Ibu (masing-masing 1 lembar).</li>
                            <li>Pas Foto 3x4 Background Merah (4 lembar).</li>
                        </ul>
                    </div>

                    <!-- Right Column: Registration Procedure -->
                    <div class="space-y-1">
                        <h4 class="font-bold text-slate-800 border-b border-slate-200 pb-0.5 print:text-black">Ketentuan & Prosedur:</h4>
                        <ul class="list-disc list-inside space-y-0.5 text-[9.5px] leading-relaxed text-slate-600 print:text-black">
                            <li>Berkas dimasukkan dalam Map Snelhechter Kertas:
                                <ul class="list-none pl-3.5 space-y-0.5 font-semibold text-slate-700 print:text-black">
                                    <li>- Map Warna Kuning: Calon Siswa Putra</li>
                                    <li>- Map Warna Merah: Calon Siswa Putri</li>
                                </ul>
                            </li>
                            <li>Hadir ke Sekretariat PPDB didampingi Orang Tua / Wali.</li>
                            <li>Daftar ulang maksimal 7 hari setelah pengumuman.</li>
                            <li>Melakukan pengukuran seragam sekolah langsung di lokasi.</li>
                        </ul>
                    </div>
                </div>

                <!-- COMPACT HORIZONTAL TIMELINE PPDB -->
                <div class="mt-3.5 pt-2 border-t border-slate-200 print:border-slate-300">
                    <h4 class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 print:text-black">Alur Kegiatan PPDB Online</h4>
                    <div class="flex items-center justify-between text-[9px] text-slate-700 font-medium print:text-black max-w-lg mx-auto">
                        <div class="flex items-center space-x-1">
                            <span class="w-3 h-3 rounded-full bg-blue-900 text-white flex items-center justify-center text-[7px] font-bold print:bg-black">1</span>
                            <span class="font-bold">Pendaftaran Online <span class="text-emerald-600 font-semibold">(Selesai)</span></span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-300 text-[7px]"></i>
                        <div class="flex items-center space-x-1">
                            <span class="w-3 h-3 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center text-[7px] font-bold print:bg-slate-200">2</span>
                            <span>Verifikasi Berkas</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-300 text-[7px]"></i>
                        <div class="flex items-center space-x-1">
                            <span class="w-3 h-3 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center text-[7px] font-bold print:bg-slate-200">3</span>
                            <span>Daftar Ulang Fisik</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-300 text-[7px]"></i>
                        <div class="flex items-center space-x-1">
                            <span class="w-3 h-3 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center text-[7px] font-bold print:bg-slate-200">4</span>
                            <span>Masa Orientasi</span>
                        </div>
                    </div>
                </div>
            </div>
            
            </div> <!-- End Top Group Wrapper -->

            <!-- FORMAL SIGNATURE ZONE -->
            <div class="grid grid-cols-2 gap-4 text-xs mt-5 pt-3.5 border-t border-dashed border-slate-200 text-slate-700 print:border-black">
                
                <!-- Left Signature (Parent) -->
                <div class="flex flex-col items-center justify-between text-center min-h-[85px]">
                    <span class="font-semibold text-[9.5px] print:text-black">Mengetahui,<br>Orang Tua / Wali Siswa</span>
                    <div class="w-28 border-b border-slate-400 mt-8 print:border-black"></div>
                </div>

                <!-- Right Signature (Committee TTE) -->
                <div class="flex flex-col items-center justify-between text-center min-h-[85px] relative">
                    <span class="font-semibold text-[9.5px] print:text-black">Limpung, {{ now()->translatedFormat('d F Y') }}<br>Panitia PPDB MAM Limpung</span>
                    
                    <div class="my-1 flex flex-col items-center justify-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode(route('frontend.ppdb.verify', ['nomor_registrasi' => $ppdb_siswa->nomor_registrasi])) }}" 
                             class="w-12 h-12 object-contain border border-slate-200 p-0.5 print:border-black" 
                             alt="TTE QR Code">
                        <span class="text-[6px] text-slate-400 font-mono tracking-tighter uppercase mt-0.5 leading-none print:text-black">TTE Terverifikasi</span>
                    </div>

                    <span class="text-[8px] font-bold text-slate-800 tracking-wider uppercase leading-none print:text-black">Dokumen Sah Digital</span>
                </div>
            </div>

            <!-- Document Footer Meta -->
            <div class="mt-5 text-center text-[8px] text-slate-400 border-t border-slate-100 pt-2 flex justify-between items-center print:text-black print:border-black print:border-t">
                <span>Dokumen resmi sistem PPDB Online MAS Muhammadiyah Limpung.</span>
                <span class="font-mono">{{ $ppdb_siswa->created_at->translatedFormat('d/m/Y - H:i') }} WIB</span>
            </div>
        </div>

        <!-- Extra Professional Help Notice -->
        <div class="mt-4 text-center text-[10px] text-slate-400 print:hidden">
            <p>Butuh bantuan pendaftaran? Hubungi Panitia PPDB di <span class="text-blue-900 font-bold hover:underline cursor-pointer">+62 812-3456-789</span></p>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Hide navbar, footer, and top elements during print */
        nav, footer, .print\:hidden, #navbar, header {
            display: none !important;
        }
        
        body {
            background-color: white !important;
            color: black !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .min-h-screen {
            min-height: auto !important;
            padding: 0 !important;
            background: white !important;
        }

        #printArea {
            border: none !important;
            box-shadow: none !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            position: relative !important;
            page-break-inside: avoid !important;
        }
</style>

@if(request()->has('print'))
<script>
    window.addEventListener('DOMContentLoaded', () => {
        // Beri jeda kecil agar loading assets & gambar logo/QR selesai sempurna sebelum dialog print muncul
        setTimeout(() => {
            window.print();
        }, 500);
    });
</script>
@endif
@endsection