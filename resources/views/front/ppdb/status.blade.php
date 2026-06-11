@extends('layouts.app')

@section('content')
@include('shared.ppdb.print.background-print')
    <!-- Inject Google Fonts & Custom Style -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');
        .font-jakarta {
            font-family: 'Plus+Jakarta+Sans', 'Inter', sans-serif;
        }
    </style>

    <div class="font-jakarta bg-slate-50 min-h-[85vh] py-16 px-4 sm:px-6 lg:px-8 flex flex-col justify-start">
        <div class="max-w-xl mx-auto w-full">
            
            <!-- Branding Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-900/5 text-blue-900 rounded-2xl mb-4 border border-blue-900/10">
                    <i class="fa-solid fa-file-invoice text-2xl"></i>
                </div>
                <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight">Cek Status Pendaftaran</h1>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mt-1.5 leading-relaxed">
                    Sistem Lacak & Cetak Mandiri PPDB Online <br> MAS Muhammadiyah Limpung
                </p>
            </div>

            @if(isset($ppdb_siswa))
                <!-- ==================== DISPLAY RESULTS STATE ==================== -->
                <div class="bg-white border border-slate-200 shadow-xl p-6 sm:p-8 space-y-6 relative overflow-hidden transition-all duration-300">
                    
                    <!-- Decorative Badge Background Accent -->
                    <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full opacity-10 bg-slate-200"></div>

                    <!-- Status Notification Panels -->
                    @if($ppdb_siswa->status == 'diterima')
                        <!-- DITERIMA STATUS -->
                        <div class="bg-emerald-50 border border-emerald-250 p-5 text-emerald-950 flex items-start gap-4">
                            <div class="w-10 h-10 bg-emerald-600 text-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-600/10">
                                <i class="fa-solid fa-circle-check text-lg"></i>
                            </div>
                            <div class="flex-grow">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-emerald-700">Status Pendaftaran</span>
                                <h3 class="text-base font-extrabold tracking-tight mt-0.5">Selamat! Anda Dinyatakan Diterima</h3>
                                <p class="text-xs text-emerald-800/90 leading-relaxed mt-2 font-medium">
                                    Selamat bergabung di MAS Muhammadiyah Limpung! Berkas pendaftaran Anda telah diverifikasi oleh Panitia dan dinyatakan **Lolos Seleksi**.
                                </p>
                                <div class="mt-4 border-t border-emerald-200/50 pt-3">
                                    <h4 class="text-[11px] font-bold uppercase tracking-wider text-emerald-900 mb-1">Langkah Selanjutnya (Daftar Ulang):</h4>
                                    <ul class="text-[11px] text-emerald-800/90 list-disc list-inside space-y-1">
                                        <li>Cetak Kartu Bukti Pendaftaran di bawah ini.</li>
                                        <li>Bawa berkas persyaratan fisik dalam map kertas (Kuning untuk Putra, Merah untuk Putri).</li>
                                        <li>Kunjungi Sekretariat PPDB untuk pengukuran seragam dan daftar ulang.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @elseif($ppdb_siswa->status == 'ditolak')
                        <!-- DITOLAK STATUS -->
                        <div class="bg-red-50 border border-red-250 p-5 text-red-950 flex items-start gap-4">
                            <div class="w-10 h-10 bg-red-600 text-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-red-600/10">
                                <i class="fa-solid fa-circle-xmark text-lg"></i>
                            </div>
                            <div class="flex-grow">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-red-700">Status Pendaftaran</span>
                                <h3 class="text-base font-extrabold tracking-tight mt-0.5">Berkas Perlu Perbaikan</h3>
                                <p class="text-xs text-red-800/90 leading-relaxed mt-2 font-medium">
                                    Saat ini berkas pendaftaran Anda belum disetujui oleh panitia karena berkas belum lengkap atau memerlukan perbaikan/klarifikasi.
                                </p>
                                @if($ppdb_siswa->catatan_admin)
                                    <div class="mt-4 bg-white/60 border border-red-200 p-3 rounded-sm">
                                        <span class="block text-[9px] font-bold text-red-800 uppercase tracking-wider">Catatan Panitia PPDB:</span>
                                        <p class="text-xs text-red-900 font-semibold mt-1 italic">
                                            "{{ $ppdb_siswa->catatan_admin }}"
                                        </p>
                                    </div>
                                @endif
                                <div class="mt-4 pt-3 border-t border-red-200/50">
                                    <p class="text-[11px] text-red-800/90 font-medium">
                                        Silakan kunjungi madrasah atau hubungi sekretariat PPDB dengan membawa berkas asli untuk melakukan perbaikan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- PENDING STATUS (VERIFICATION PROGRESS) -->
                        <div class="bg-amber-50 border border-amber-250 p-5 text-amber-950 flex items-start gap-4">
                            <div class="w-10 h-10 bg-amber-500 text-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/10 animate-pulse">
                                <i class="fa-solid fa-hourglass-half text-lg"></i>
                            </div>
                            <div class="flex-grow">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-amber-800">Status Pendaftaran</span>
                                <h3 class="text-base font-extrabold tracking-tight mt-0.5">Sedang Dalam Proses Verifikasi</h3>
                                <p class="text-xs text-amber-900/95 leading-relaxed mt-2 font-medium">
                                    Berkas pendaftaran Anda telah masuk ke database sekolah. Panitia sedang meninjau kelengkapan dokumen dan data diri Anda.
                                </p>
                                <p class="text-[11px] text-amber-800 font-medium mt-3 italic">
                                    *Harap periksa halaman ini secara berkala untuk mengetahui perkembangan verifikasi pendaftaran Anda.
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Student Identity Detail Grid -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Identitas Terdaftar</h4>
                        
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider">No. Pendaftaran</span>
                                <span class="font-mono font-extrabold text-slate-900 uppercase tracking-wide">{{ $ppdb_siswa->nomor_registrasi }}</span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider">Nama Lengkap</span>
                                <span class="font-bold text-slate-900">{{ $ppdb_siswa->nama_lengkap }}</span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider">NISN</span>
                                <span class="font-mono font-semibold text-slate-900">{{ $ppdb_siswa->nisn }}</span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider">Asal Sekolah</span>
                                <span class="font-semibold text-slate-900">{{ $ppdb_siswa->sekolah_asal }}</span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider">Ukuran Baju</span>
                                <span class="font-semibold text-slate-900 uppercase">{{ $ppdb_siswa->ukuran_baju }}</span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Daftar</span>
                                <span class="font-semibold text-slate-900">{{ $ppdb_siswa->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-3">
                        <button type="button"
                            id="btn-print-kartu-ppdb"
                            data-print-url="{{ $printDocumentUrl }}"
                            class="flex-1 bg-blue-900 text-white py-3.5 px-6 font-bold hover:bg-black transition-all duration-300 uppercase tracking-widest text-xs inline-flex items-center justify-center gap-2.5 shadow-xl shadow-blue-900/10 rounded-none disabled:opacity-60 disabled:cursor-wait">
                            <i class="fa-solid fa-print text-sm"></i>
                            <span>Cetak Kartu PPDB</span>
                        </button>
                        <a href="{{ route('frontend.ppdb.status') }}" 
                           class="border-2 border-slate-200 text-slate-800 py-3 px-6 font-bold hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all duration-300 uppercase tracking-widest text-xs text-center rounded-none">
                            Kembali Cari
                        </a>
                    </div>

                </div>
            @else
                <!-- ==================== SEARCH FORM STATE ==================== -->
                <div class="bg-white border border-slate-200 shadow-xl p-6 sm:p-8">
                    
                    <form action="{{ route('frontend.ppdb.check-status') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <!-- General Error Alert -->
                        @if($errors->has('error'))
                            <div class="bg-red-50 border-l-4 border-red-600 p-4 text-red-950 flex items-start gap-3">
                                <i class="fa-solid fa-triangle-exclamation mt-0.5 text-red-700"></i>
                                <span class="text-xs font-semibold leading-relaxed">{{ $errors->first('error') }}</span>
                            </div>
                        @endif

                        <!-- Single Search Input -->
                        <div class="space-y-1.5">
                            <label for="keyword" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-none">
                                Nomor Pendaftaran / NISN / Nama Lengkap
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                                </div>
                                <input type="text"
                                       name="keyword"
                                       id="keyword"
                                       value="{{ old('keyword') }}"
                                       placeholder="Contoh: PPDB-2026-XXXXX atau 0123456789 atau Budi Santoso"
                                       autocomplete="off"
                                       class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-300 text-slate-900 font-mono placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-900 focus:border-blue-900 text-sm font-semibold rounded-none tracking-wide @error('keyword') border-red-500 @enderror">
                            </div>
                            @error('keyword')
                                <p class="text-[10px] font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info -->
                        <div class="bg-blue-50/50 border border-blue-100 p-3.5 text-blue-950 flex items-start gap-3">
                            <i class="fa-solid fa-circle-info mt-0.5 text-blue-800 text-sm"></i>
                            <p class="text-[10px] text-blue-850 font-medium leading-relaxed">
                                Masukkan salah satu dari: <strong>Nomor Pendaftaran</strong> (contoh: PPDB-2026-XXXXX), <strong>NISN</strong> (10 digit), atau <strong>Nama Lengkap</strong>.
                            </p>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                                class="w-full bg-blue-900 text-white py-4 px-6 font-bold hover:bg-black transition-all duration-300 uppercase tracking-widest text-xs inline-flex items-center justify-center gap-2.5 shadow-xl shadow-blue-900/10 rounded-none">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            Cari Status Pendaftaran
                        </button>

                    </form>

                </div>
            @endif

            <!-- Back Link to PPDB Landing Page -->
            <div class="text-center mt-6">
                <a href="{{ route('frontend.ppdb.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-blue-900 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali Ke Halaman Utama PPDB
                </a>
            </div>

        </div>
    </div>

@if(isset($printDocumentUrl))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('btn-print-kartu-ppdb');
        if (!btn || !window.PpdbBackgroundPrint) {
            return;
        }

        btn.addEventListener('click', async function () {
            btn.disabled = true;
            try {
                await PpdbBackgroundPrint.printFromUrl(btn.dataset.printUrl, {
                    loadingLabel: 'Menyiapkan kartu PPDB...',
                    errorLabel: 'Gagal mencetak. Coba lagi.',
                });
            } finally {
                btn.disabled = false;
            }
        });
    });
</script>
@endif
@endsection
