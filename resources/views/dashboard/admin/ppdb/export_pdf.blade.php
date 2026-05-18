<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_PPDB_MAM_Limpung_{{ $year }}_{{ date('YmdHis') }}</title>
    <style>
        /* Base Setup */
        body {
            font-family: Arial, sans-serif;
            color: #1e293b;
            background-color: #fff;
            margin: 0;
            padding: 10px;
            font-size: {{ $orientation === 'portrait' ? '8.5px' : '10px' }};
            line-height: 1.25;
        }

        /* Kop Surat (School Official Letterhead) */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 15px;
            position: relative;
        }
        .kop-logo {
            width: 55px;
            height: auto;
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        .kop-text {
            text-align: center;
            flex-grow: 1;
            padding: 0 10px 0 75px;
        }
        .kop-text h2 {
            font-family: Arial, sans-serif;
            font-size: {{ $orientation === 'portrait' ? '11px' : '12px' }};
            margin: 0;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .kop-text h1 {
            font-family: Arial, sans-serif;
            font-size: {{ $orientation === 'portrait' ? '14px' : '17px' }};
            margin: 3px 0;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .kop-text p {
            margin: 2px 0 0 0;
            font-size: {{ $orientation === 'portrait' ? '8px' : '9px' }};
            font-style: italic;
            color: #475569;
        }

        /* Report Meta Title */
        .report-header {
            text-align: center;
            margin-bottom: 15px;
        }
        .report-header h3 {
            font-size: {{ $orientation === 'portrait' ? '11px' : '13px' }};
            margin: 0 0 5px 0;
            text-transform: uppercase;
            font-weight: bold;
            text-decoration: underline;
        }
        .report-meta {
            display: flex;
            justify-content: space-between;
            font-family: Arial, sans-serif;
            font-size: {{ $orientation === 'portrait' ? '8px' : '9px' }};
            color: #334155;
            margin-bottom: 10px;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 5px;
        }

        /* Table Design (Clean Ledger Sheet) */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: auto;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: {{ $orientation === 'portrait' ? '4px 5px' : '6px 8px' }};
            text-align: left;
            vertical-align: middle;
            word-wrap: break-word;
        }
        th {
            background-color: #f8fafc !important;
            font-weight: bold;
            text-transform: uppercase;
            font-size: {{ $orientation === 'portrait' ? '7.5px' : '8.5px' }};
            text-align: center;
            color: #334155;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
        
        /* Status Badges for Print */
        .status-badge {
            font-weight: bold;
            font-size: {{ $orientation === 'portrait' ? '7.5px' : '8.5px' }};
            text-transform: uppercase;
        }
        .status-diterima {
            color: #059669;
        }
        .status-pending {
            color: #d97706;
        }
        .status-ditolak {
            color: #dc2626;
        }

        /* Page Layout & Print Optimization */
        @page {
            size: A4 {{ $orientation }};
            margin: 0.8cm;
        }

        @media print {
            body {
                padding: 0;
                background-color: #fff;
                color: #000;
                font-size: {{ $orientation === 'portrait' ? '8px' : '9.5px' }};
            }
            th {
                background-color: #e2e8f0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            tr:nth-child(even) {
                background-color: #f8fafc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
        }

        /* Floating back button for screen preview */
        .preview-controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            background: #fff;
            padding: 10px 15px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        .preview-controls button {
            background: #4f45b2;
            color: white;
            border: none;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: bold;
            cursor: pointer;
        }
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

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                @foreach($selectedFields as $f)
                    <th>
                        @switch($f)
                            @case('nomor_registrasi') No. Registrasi @break
                            @case('nama_lengkap') Nama Lengkap @break
                            @case('nisn') NISN @break
                            @case('jenis_kelamin') L/P @break
                            @case('tempat_lahir') Tempat Lahir @break
                            @case('tanggal_lahir') Tgl Lahir @break
                            @case('sekolah_asal') Sekolah Asal @break
                            @case('ukuran_baju') Seragam @break
                            @case('nomor_hp') No. HP/WA @break
                            @case('email') Email @break
                            @case('alamat_lengkap') Alamat Rumah @break
                            @case('nama_ayah') Nama Ayah @break
                            @case('nama_ibu') Nama Ibu @break
                            @case('status') Status @break
                            @case('submitted_at') Tgl Daftar @break
                            @default
                                @php
                                    $matched = collect($customFields)->firstWhere('id', $f);
                                    $label = $matched ? $matched['label'] : strtoupper($f);
                                    if ($f === 'nama_wali') {
                                        $label = 'Nama Wali';
                                    }
                                    echo $label;
                                @endphp
                        @endswitch
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    @foreach($selectedFields as $f)
                        @switch($f)
                            @case('nomor_registrasi')
                                <td class="center font-mono" style="font-weight: bold;">{{ $student->nomor_registrasi }}</td>
                                @break
                            @case('nama_lengkap')
                                <td style="font-weight: bold; text-transform: uppercase;">{{ $student->nama_lengkap }}</td>
                                @break
                            @case('nisn')
                                <td class="center font-mono">{{ $student->nisn }}</td>
                                @break
                            @case('jenis_kelamin')
                                <td class="center">{{ $student->jenis_kelamin }}</td>
                                @break
                            @case('tempat_lahir')
                                <td>{{ $student->tempat_lahir }}</td>
                                @break
                            @case('tanggal_lahir')
                                <td class="center font-mono">{{ $student->tanggal_lahir?->format('d-m-Y') ?? '-' }}</td>
                                @break
                            @case('sekolah_asal')
                                <td style="text-transform: uppercase;">{{ $student->sekolah_asal }}</td>
                                @break
                            @case('ukuran_baju')
                                <td class="center font-mono" style="font-weight: bold;">{{ $student->ukuran_baju ?? '-' }}</td>
                                @break
                            @case('nomor_hp')
                                <td class="center">{{ $student->nomor_hp }}</td>
                                @break
                            @case('email')
                                <td>{{ $student->email }}</td>
                                @break
                            @case('alamat_lengkap')
                                <td style="font-size: 8px;">{{ $student->alamat_lengkap }}</td>
                                @break
                            @case('nama_ayah')
                                <td>{{ $student->nama_ayah }}</td>
                                @break
                            @case('nama_ibu')
                                <td>{{ $student->nama_ibu }}</td>
                                @break
                            @case('status')
                                <td class="center status-badge status-{{ $student->status ?? 'pending' }}">
                                    {{ $student->status === 'diterima' ? 'LULUS' : ($student->status === 'ditolak' ? 'TOLAK' : 'PROSES') }}
                                </td>
                                @break
                            @case('submitted_at')
                                <td class="center font-mono">{{ $student->submitted_at?->format('d-m-Y H:i') ?? '-' }}</td>
                                @break
                            @default
                                <td>
                                    @php
                                        $val = $student->additional_fields[$f] ?? '';
                                        if (is_array($val)) {
                                            $val = implode(', ', $val);
                                        }
                                        echo $val;
                                    @endphp
                                </td>
                        @endswitch
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($selectedFields) + 1 }}" class="center" style="padding: 20px; font-style: italic; color: #64748b;">
                        Belum ada data pendaftar yang sesuai dengan filter pencarian.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Uniform Size Summary Section (Page 2 / Bottom of Report) -->
    <div style="page-break-before: always;"></div>

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
