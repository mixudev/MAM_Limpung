<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_PPDB_MAM_Limpung_{{ $year }}_{{ date('YmdHis') }}</title>
    <style>
        @include('dashboard.admin.ppdb.partials.pdf.styles');
    </style>
</head>
<body>

    <!-- Preview controller for screen reading -->
    <div class="preview-controls no-print">
        <button onclick="window.close()">Tutup Pratinjau</button>
        <button onclick="window.print()" style="background: #0284c7; margin-left: 5px;">Cetak Dokumen</button>
    </div>

    <div class="print-ledger-wrapper">
        <!-- Official Kop Surat -->
        <div class="kop-surat">
            <img class="kop-logo" src="{{ asset('assets/img/logo.png') }}" alt="MAM Limpung Logo">
            <div class="kop-text">
                <h2>Majelis Pendidikan Dasar dan Menengah Muhammadiyah</h2>
                <h1>Madrasah Aliyah Muhammadiyah Limpung</h1>
                <h2>Terakreditasi A (Unggul)</h2>
                <p>Alamat: Jl. Raya Limpung No. 12 Limpung, Kab. Batang, Jawa Tengah | Telp/WA: +62 823-2495-2365 | Email: mamlimpung@gmail.com</p>
            </div>
        </div>

        <!-- Report Header -->
        <div class="report-header">
            <h3>Buku Ledger Pendaftaran Calon Siswa Baru (PPDB)</h3>
            <span style="font-family: Arial, sans-serif; font-size: 10px; font-weight: bold;">Tahun Pelajaran: {{ $year }}/{{ $year + 1 }}</span>
        </div>

        <!-- Meta Details Bar -->
        <div class="report-meta">
            <div>
                <span><strong>Status Seleksi:</strong> {{ $status === 'all' ? 'SEMUA STATUS' : strtoupper($status) }}</span>
            </div>
            <div>
                <span><strong>Total Calon Siswa:</strong> {{ count($students) }} Siswa</span>
            </div>
            <div>
                <span><strong>Tanggal Cetak:</strong> {{ date('d-m-Y H:i:s') }}</span>
            </div>
        </div>

        @include('dashboard.admin.ppdb.partials.pdf.ledger_table')

        <!-- Uniform Size Summary Section (Page 2 / Bottom of Report) -->
        <div style="page-break-before: always;"></div>

        @include('dashboard.admin.ppdb.partials.pdf.seragam_table')

    </div><!-- /print-ledger-wrapper -->

    <!-- Auto Print Script Trigger -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
