@include('shared.ppdb.print.kop', [
    'tahunAjaran' => (int) $year,
    'logoSize' => '55px',
    'docTitle' => 'Laporan Ringkasan Kebutuhan Ukuran Seragam Olahraga Siswa Baru',
    'docSubtitle' => 'Tahun Pelajaran ' . $year . '/' . ($year + 1),
])

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
            continue;
        }
        if (isset($sizeCounts[$s])) {
            $sizeCounts[$s]++;
        } else {
            $sizeCounts['LAINNYA']++;
        }
        $totalWithSizes++;
    }

    $totalStudents = count($students);
    $totalMissingSizes = $totalStudents - $totalWithSizes;
    $allSizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'LAINNYA', 'BELUM MENGISI'];
@endphp

<div class="report-meta">
    <div><strong>Total Calon Siswa:</strong> {{ $totalStudents }}</div>
    <div><strong>Seragam Terdata:</strong> {{ $totalWithSizes }}</div>
    <div><strong>Belum Isi Ukuran:</strong> {{ $totalMissingSizes }}</div>
</div>

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
                <td class="center text-bold">{{ $size }}</td>
                <td class="center font-mono text-bold">{{ $count }}</td>
                <td class="center font-mono">{{ number_format($percentage, 1) }}%</td>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px; height: 12px;">
                        <div style="background-color: #e2e8f0; width: 180px; height: 8px; overflow: hidden;">
                            <div style="background-color: #4f45b2; width: {{ $percentage }}%; height: 100%;"></div>
                        </div>
                        <span style="font-size: 7.5pt; font-weight: bold; font-family: monospace;">{{ number_format($percentage, 1) }}%</span>
                    </div>
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" class="right text-bold">Total Keseluruhan</td>
            <td class="center font-mono text-bold">{{ $totalStudents }}</td>
            <td class="center font-mono text-bold">100.0%</td>
            <td></td>
        </tr>
    </tbody>
</table>
