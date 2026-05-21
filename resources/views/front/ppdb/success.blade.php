@extends('layouts.app')

@section('content')
@include('shared.ppdb.print.background-print')

<script>
    localStorage.removeItem('ppdb_form_draft');
</script>

@php
    $tahunAjaran = $general['tahun_ajaran'] ?? ($ppdb_siswa->submitted_at?->year ?? (int) date('Y'));
@endphp

<div class="min-h-screen py-10 px-4 bg-slate-50">
    <div class="max-w-2xl mx-auto">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 text-emerald-700 rounded-full mb-4">
                <i class="fa-solid fa-circle-check text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Pendaftaran Berhasil!</h1>
            <p class="text-sm text-slate-600">
                Data pendaftaran telah tersimpan. Simpan nomor registrasi Anda dan cetak bukti pendaftaran resmi.
            </p>
        </div>

        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 text-sm text-slate-700">
            <p>
                Bukti pendaftaran juga dikirim ke email:
                <strong class="text-emerald-900">{{ $ppdb_siswa->email }}</strong>
            </p>
        </div>

        <div class="bg-white border border-slate-200 shadow-sm p-6 mb-6 space-y-4">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nomor Registrasi</span>
                <p class="font-mono font-black text-xl text-emerald-800 mt-1">{{ $ppdb_siswa->nomor_registrasi }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nama Lengkap</span>
                    <p class="font-semibold text-slate-900 mt-0.5">{{ $ppdb_siswa->nama_lengkap }}</p>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">NISN</span>
                    <p class="font-mono font-semibold text-slate-900 mt-0.5">{{ $ppdb_siswa->nisn }}</p>
                </div>
            </div>
            <p class="text-xs text-slate-500 border-t border-slate-100 pt-3">
                Tahun pelajaran {{ $tahunAjaran }}/{{ $tahunAjaran + 1 }} — dokumen cetak memakai kop resmi sekolah.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button type="button"
                id="btn-print-bukti"
                data-print-url="{{ $printDocumentUrl }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold uppercase tracking-wider transition-colors disabled:opacity-60 disabled:cursor-wait">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Bukti Pendaftaran</span>
            </button>
            <a href="{{ route('frontend.ppdb.status') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-slate-300 text-slate-700 text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors">
                Cek Status Pendaftaran
            </a>
            <a href="{{ route('frontend.home') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wider hover:bg-slate-200 transition-colors">
                Beranda
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('btn-print-bukti');
        if (!btn || !window.PpdbBackgroundPrint) {
            return;
        }

        btn.addEventListener('click', async function () {
            const url = btn.dataset.printUrl;
            if (!url) {
                return;
            }

            btn.disabled = true;
            try {
                await PpdbBackgroundPrint.printFromUrl(url, {
                    loadingLabel: 'Menyiapkan bukti pendaftaran...',
                    errorLabel: 'Gagal mencetak. Coba lagi.',
                });
            } finally {
                btn.disabled = false;
            }
        });
    });
</script>
@endsection
