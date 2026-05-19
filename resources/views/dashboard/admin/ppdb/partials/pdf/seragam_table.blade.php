<!-- Kop Surat for professional continuity -->
<div class="kop-surat">
    <img class="kop-logo" src="{{ asset('assets/img/logo.png') }}" alt="MAM Limpung Logo">
    <div class="kop-text">
        <h2>Majelis Pendidikan Dasar dan Menengah Muhammadiyah</h2>
        <h1>Madrasah Aliyah Muhammadiyah Limpung</h1>
        <h2>Terakreditasi A (Unggul)</h2>
        <p>Alamat: Jl. Raya Limpung No. 12 Limpung, Kab. Batang, Jawa Tengah | Telp/WA: +62 823-2495-2365 | Email: mamlimpung@gmail.com</p>
    </div>
</div>

<div class="report-header">
    <h3>Laporan Ringkasan Kebutuhan Ukuran Seragam Olahraga Siswa Baru</h3>
    <span style="font-family: Arial, sans-serif; font-size: 10px; font-weight: bold;">Tahun Pelajaran: {{ $year }}/{{ $year + 1 }}</span>
</div>

@php
    $sizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
    $sizeCounts = [];
    foreach ($sizes as $s) {
        $sizeCounts[$s] = 0;
    }
    $sizeCounts['LAINNYA'] = 0;
    $totalWithSizes = 0;

    foreach ($students as $student) {
        $s = strtoupper(trim($student->ukuran_baju ?? ''));
        if (empty($s) || $s === '-') {
            // empty
        } else {
            if (isset($sizeCounts[$s])) {
                $sizeCounts[$s]++;
            } else {
                $sizeCounts['LAINNYA']++;
            }
            $totalWithSizes++;
        }
    }

    $totalStudents = count($students);
    $totalMissingSizes = $totalStudents - $totalWithSizes;
    $allSizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'LAINNYA', 'BELUM MENGISI'];
@endphp

<!-- KPI blocks for printable PDF dashboard -->
<div style="display: flex; justify-content: center; gap: 20px; margin-bottom: 25px; margin-top: 15px;">
    <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 10px 20px; text-align: center; width: 160px; border-radius: 4px;">
        <span style="display: block; font-size: 8px; font-weight: bold; color: #166534; text-transform: uppercase;">Total Calon Siswa</span>
        <span style="display: block; font-size: 16px; font-weight: 800; color: #14532d; font-family: monospace; margin-top: 4px;">{{ $totalStudents }}</span>
    </div>
    <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; padding: 10px 20px; text-align: center; width: 160px; border-radius: 4px;">
        <span style="display: block; font-size: 8px; font-weight: bold; color: #1e40af; text-transform: uppercase;">Seragam Terdata</span>
        <span style="display: block; font-size: 16px; font-weight: 800; color: #1e3a8a; font-family: monospace; margin-top: 4px;">{{ $totalWithSizes }}</span>
    </div>
    <div style="background-color: #fff7ed; border: 1px solid #fed7aa; padding: 10px 20px; text-align: center; width: 160px; border-radius: 4px;">
        <span style="display: block; font-size: 8px; font-weight: bold; color: #c2410c; text-transform: uppercase;">Belum Isi Ukuran</span>
        <span style="display: block; font-size: 16px; font-weight: 800; color: #7c2d12; font-family: monospace; margin-top: 4px;">{{ $totalMissingSizes }}</span>
    </div>
</div>

<!-- Summary Table -->
<table>
    <thead>
        <tr>
            <th style="width: 40px;">No</th>
            <th style="width: 150px;">Ukuran Seragam</th>
            <th style="width: 100px;">Jumlah Calon Siswa</th>
            <th style="width: 100px;">Persentase</th>
            <th>Visualisasi Grafik Distribusi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($allSizes as $index => $size)
            @php
                $count = ($size === 'BELUM MENGISI') ? $totalMissingSizes : $sizeCounts[$size];
                $percentage = $totalStudents > 0 ? ($count / $totalStudents) * 100 : 0;
            @endphp
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td style="font-weight: bold; text-align: center; color: #1e293b;">{{ $size }}</td>
                <td class="center font-mono" style="font-weight: bold;">{{ $count }}</td>
                <td class="center font-mono" style="color: #475569;">{{ number_format($percentage, 1) }}%</td>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px; height: 12px; margin: 2px 0;">
                        <div style="background-color: #e2e8f0; width: 180px; height: 8px; border-radius: 9999px; overflow: hidden; position: relative;">
                            <div style="background-color: #4f45b2; width: {{ $percentage }}%; height: 100%; border-radius: 9999px;"></div>
                        </div>
                        <span style="font-size: 7.5px; font-weight: bold; color: #4f45b2; font-family: monospace;">{{ number_format($percentage, 1) }}%</span>
                    </div>
                </td>
            </tr>
        @endforeach
        <!-- Total Sum row -->
        <tr style="background-color: #e2e8f0 !important; font-weight: bold;">
            <td colspan="2" style="text-align: right; text-transform: uppercase; padding-right: 15px; color: #1e293b;">Total Keseluruhan</td>
            <td class="center font-mono">{{ $totalStudents }}</td>
            <td class="center font-mono">100.0%</td>
            <td>
                <div style="display: flex; align-items: center; gap: 8px; height: 12px;">
                    <div style="background-color: #cbd5e1; width: 180px; height: 8px; border-radius: 9999px; overflow: hidden; position: relative;">
                        <div style="background-color: #475569; width: 100%; height: 100%; border-radius: 9999px;"></div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
