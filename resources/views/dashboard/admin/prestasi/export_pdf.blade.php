<x-print.layout
    :title="'Laporan_Prestasi_MAM_Limpung_' . date('YmdHis')"
    :orientation="$orientation"
    :auto-print="false"
>
    <x-slot:styles>
        <style>
            .print-wrapper {
                width: 100%;
                font-family: Arial, sans-serif;
            }
            .report-meta {
                display: flex;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 6px;
                font-size: {{ $orientation === 'landscape' ? '9pt' : '8.5pt' }};
                margin-top: 10px;
                margin-bottom: 12px;
                border-bottom: 1px dashed #000;
                padding-bottom: 6px;
            }
            .print-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
                font-size: {{ $orientation === 'landscape' ? '9pt' : '8.5pt' }};
            }
            .print-table th, .print-table td {
                border: 1px solid #333;
                padding: 6px 8px;
                vertical-align: top;
            }
            .print-table th {
                background-color: #f1f5f9 !important;
                font-weight: bold;
                text-transform: uppercase;
                text-align: center;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print-table tr:nth-child(even) td {
                background-color: #f8fafc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .text-center { text-align: center; }
            .font-bold { font-weight: bold; }
        </style>
    </x-slot:styles>

    <x-print.action-bar mode="preview" />

    <div class="print-page print-wrapper">
        @include('shared.ppdb.print.kop', [
            'logoSize' => '55px',
            'docTitle' => 'Laporan Rekapitulasi Prestasi Siswa & Tim',
            'docSubtitle' => 'MA Muhammadiyah Limpung Kabupaten Batang',
        ])

        <div class="report-meta">
            <div>
                <strong>Tingkat:</strong> {{ $tingkat === 'all' ? 'SEMUA TINGKAT' : strtoupper($tingkat) }}
            </div>
            <div>
                <strong>Jenis:</strong> {{ $jenis === 'all' ? 'SEMUA JENIS' : ($jenis === 'akademik' ? 'AKADEMIK' : 'NON-AKADEMIK') }}
            </div>
            <div>
                <strong>Total Prestasi:</strong> {{ count($prestasis) }} Rekor
            </div>
            <div>
                <strong>Tanggal Cetak:</strong> {{ now()->format('d-m-Y H:i:s') }}
            </div>
        </div>

        <table class="print-table">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 22%;">Judul Prestasi</th>
                    <th style="width: 16%;">Peraih (Siswa / Tim)</th>
                    <th style="width: 8%; text-align: center;">Kelas</th>
                    <th style="width: 10%; text-align: center;">Tingkat</th>
                    <th style="width: 10%; text-align: center;">Jenis</th>
                    <th style="width: 8%; text-align: center;">Tahun</th>
                    <th style="width: 16%;">Penyelenggara</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestasis as $index => $pres)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="font-bold">{{ $pres->judul }}</td>
                        <td>{{ $pres->peraih }}</td>
                        <td class="text-center">{{ $pres->kelas ?? '-' }}</td>
                        <td class="text-center">{{ $pres->tingkatLabel() }}</td>
                        <td class="text-center">{{ $pres->jenis === 'akademik' ? 'Akademik' : 'Non-Akademik' }}</td>
                        <td class="text-center font-mono">{{ $pres->tahun }}</td>
                        <td>{{ $pres->penyelenggara ?? '-' }}</td>
                    </tr>
                @empty
                        <tr>
                            <td colspan="8" class="text-center italic" style="padding: 20px;">
                                Tidak ada data prestasi yang ditemukan.
                            </td>
                        </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-print.layout>
