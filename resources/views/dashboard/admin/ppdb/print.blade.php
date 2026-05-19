<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIODATA_PPDB_{{ strtoupper(str_replace(' ', '_', $student->nama_lengkap)) }}</title>
    <style>
        @include('dashboard.admin.ppdb.partials.print.styles');
    </style>
</head>
<body>

    <!-- Print control bar for screen viewing -->
    <div class="print-btn-bar no-print">
        <button class="print-btn" onclick="window.print()">Cetak Dokumen</button>
        <button class="print-btn" style="background-color: #555; margin-left: 10px;" onclick="window.close()">Tutup Halaman</button>
    </div>

    <!-- Main Container -->
    <div class="print-wrapper">
        @include('dashboard.admin.ppdb.partials.print.header')
        @include('dashboard.admin.ppdb.partials.print.biodata')
        @include('dashboard.admin.ppdb.partials.print.details')
        @include('dashboard.admin.ppdb.partials.print.signatures')
    </div>

    <!-- Auto-Print Script -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
