@php
    $tahunAjaran = $student->registrationWave?->academicYear?->year ?? $student->submitted_at?->year ?? (int) date('Y');
@endphp
<x-print.layout
    :title="'BUKTI_PPDB_' . strtoupper($student->nomor_registrasi)"
    :auto-print="request()->boolean('print')"
>

    <x-slot:styles>
        <style>
            @include('dashboard.admin.ppdb.partials.print.styles')
            .doc-footer-note { page-break-inside: avoid; }
        </style>
    </x-slot:styles>

    <x-print.action-bar mode="print" label="Kartu Bukti Pendaftaran PPDB" />

    <div class="print-page print-wrapper">
        @include('shared.ppdb.print.kop', [
            'tahunAjaran' => $tahunAjaran,
            'docTitle' => 'Kartu Bukti Pendaftaran PPDB Online',
            'docSubtitle' => 'Tahun Pelajaran ' . $tahunAjaran . '/' . ($tahunAjaran + 1),
        ])

        @include('dashboard.admin.ppdb.partials.print.biodata')
        @include('dashboard.admin.ppdb.partials.print.details')
        @include('shared.ppdb.print.bukti-persyaratan')
        @include('shared.ppdb.print.signatures-bukti')
    </div>

</x-print.layout>
