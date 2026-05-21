<x-print.layout
    :title="'Laporan_PPDB_MAM_Limpung_' . $year . '_' . date('YmdHis')"
    :orientation="$orientation"
    :auto-print="false"
>
    <x-slot:styles>
        <style>
            @include('dashboard.admin.ppdb.partials.pdf.export-styles')
        </style>
    </x-slot:styles>

    <x-print.action-bar mode="preview" />

    <div class="print-page print-wrapper print-ledger-wrapper">

        @include('shared.ppdb.print.kop', [
            'tahunAjaran' => (int) $year,
            'logoSize' => '55px',
            'docTitle' => 'Buku Ledger Pendaftaran Calon Siswa Baru (PPDB)',
            'docSubtitle' => 'Tahun Pelajaran ' . $year . '/' . ($year + 1),
        ])

        <div class="report-meta">
            <div>
                <strong>Status Seleksi:</strong> {{ $status === 'all' ? 'SEMUA STATUS' : strtoupper($status) }}
            </div>
            <div>
                <strong>Total Calon Siswa:</strong> {{ count($students) }} Siswa
            </div>
            <div>
                <strong>Tanggal Cetak:</strong> {{ now()->format('d-m-Y H:i:s') }}
            </div>
        </div>

        @include('dashboard.admin.ppdb.partials.pdf.ledger_table')

        <div class="page-break"></div>

        @include('dashboard.admin.ppdb.partials.pdf.seragam_table')

    </div>

</x-print.layout>
