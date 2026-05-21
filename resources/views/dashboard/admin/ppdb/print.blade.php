<x-print.layout :title="'BIODATA_PPDB_' . strtoupper(str_replace(' ', '_', $student->nama_lengkap))">

    <x-slot:styles>
        <style>
            @include('dashboard.admin.ppdb.partials.print.styles')
        </style>
    </x-slot:styles>

    {{-- Tombol Cetak / Tutup --}}
    <x-print.action-bar mode="print" />

    {{-- Isi Dokumen --}}
    <div class="print-page print-wrapper">
        @php
            $tahunAjaran = $student->submitted_at?->year ?? (int) date('Y');
        @endphp
        @include('shared.ppdb.print.kop', [
            'tahunAjaran' => $tahunAjaran,
            'docTitle' => 'Formulir Pendaftaran Calon Siswa Baru',
            'docSubtitle' => 'Penerimaan Peserta Didik Baru (PPDB) Tahun Pelajaran ' . $tahunAjaran . '/' . ($tahunAjaran + 1),
        ])
        @include('dashboard.admin.ppdb.partials.print.biodata')
        @include('dashboard.admin.ppdb.partials.print.details')
        @include('dashboard.admin.ppdb.partials.print.signatures')
    </div>

</x-print.layout>
