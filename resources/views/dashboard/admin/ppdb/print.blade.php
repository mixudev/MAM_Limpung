<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIODATA_PPDB_{{ strtoupper(str_replace(' ', '_', $student->nama_lengkap)) }}</title>
    <style>
        /* CSS resets & A4 Print Styles to guarantee exactly 1 page layout */
        @page {
            size: A4;
            margin: 12mm 15mm 12mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10.5pt;
            line-height: 1.35;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        
        .print-wrapper {
            width: 100%;
            box-sizing: border-box;
        }
        
        /* Kop Surat (School Letterhead) */
        .kop-container {
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 3px double #000;
            padding-bottom: 6px;
            margin-bottom: 12px;
            position: relative;
        }
        .kop-logo {
            position: absolute;
            left: 5px;
            width: 75px;
            height: 75px;
            object-fit: contain;
        }
        .kop-text {
            text-align: center;
            padding-left: 85px;
            padding-right: 15px;
        }
        .kop-yayasan {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .kop-sekolah {
            font-size: 13.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 2px 0 0 0;
        }
        .kop-akreditasi {
            font-size: 8.5pt;
            font-style: italic;
            margin: 1px 0 0 0;
        }
        .kop-alamat {
            font-size: 8.5pt;
            margin: 3px 0 0 0;
        }

        /* Document Title */
        .doc-title {
            text-align: center;
            font-size: 11.5pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 0 0 3px 0;
        }
        .doc-subtitle {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            margin: 0 0 15px 0;
        }

        /* Content Layout */
        .profile-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        .biodata-table {
            width: 78%;
            border-collapse: collapse;
        }
        .biodata-table td {
            vertical-align: top;
            padding: 3px 0;
        }
        .biodata-table td.label {
            width: 32%;
        }
        .biodata-table td.separator {
            width: 3%;
            text-align: center;
        }
        .biodata-table td.value {
            font-weight: bold;
        }

        /* Photo Area */
        .photo-box {
            width: 2.6cm;
            height: 3.5cm;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 7.5pt;
            color: #555;
            text-align: center;
            box-sizing: border-box;
            background-color: #fcfcfc;
            overflow: hidden;
        }
        .photo-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Table Grid standard for data grouping */
        .section-header {
            font-size: 9.5pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1.5px solid #000;
            padding: 2px 0;
            margin-top: 12px;
            margin-bottom: 6px;
            page-break-inside: avoid;
        }

        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            border: none;
            page-break-inside: avoid;
        }
        .grid-table td {
            padding: 3.5px 0;
            border: none;
            vertical-align: top;
        }
        .grid-table td.label {
            width: 32%;
            background-color: transparent;
            font-weight: normal;
            color: #111;
        }
        .grid-table td.separator {
            width: 3%;
            text-align: center;
            color: #111;
        }
        .grid-table td.value {
            width: 65%;
            font-weight: bold;
            color: #000;
        }

        /* Signatures block */
        .signature-block {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .signature-col {
            width: 45%;
            display: flex;
            flex-direction: column;
        }
        .signature-date {
            margin-bottom: 8px;
        }
        .signature-title {
            margin-bottom: 6px;
            font-weight: bold;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Hide print triggers */
        @media print {
            .no-print {
                display: none !important;
            }
        }

        .print-btn-bar {
            background-color: #f5f5f5;
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
        }
        .print-btn {
            background-color: #4f45b2;
            color: white;
            border: none;
            padding: 6px 12px;
            font-weight: bold;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 9.5pt;
        }
        .print-btn:hover {
            background-color: #3b3394;
        }
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
        
        <!-- Kop Surat -->
        <div class="kop-container">
            <!-- Local asset logo.png -->
            <img class="kop-logo" src="{{ asset('assets/img/logo.png') }}" alt="Logo Muhammadiyah">
            
            <div class="kop-text">
                <h4 class="kop-yayasan">Pimpinan Cabang Muhammadiyah Limpung</h4>
                <h2 class="kop-sekolah">Madrasah Aliyah Muhammadiyah Limpung</h2>
                <p class="kop-akreditasi">TERAKREDITASI B | NPSN: 20363023 | Status: Swasta</p>
                <p class="kop-alamat">Jl. Raya Limpung No. 12, Limpung, Batang, Jawa Tengah 51271 - Telp: (0285) 446889</p>
            </div>
        </div>

        <!-- Document Titles -->
        <h1 class="doc-title">Formulir Pendaftaran Calon Siswa Baru</h1>
        <h3 class="doc-subtitle">Penerimaan Peserta Didik Baru (PPDB) Tahun Pelajaran {{ $student->tahun_pelajaran ?? date('Y') }}/{{ ($student->tahun_pelajaran ?? date('Y')) + 1 }}</h3>

        <!-- Biodata Section & Photo Box -->
        <div class="profile-section">
            <table class="biodata-table">
                <tr>
                    <td class="label">Nomor Registrasi</td>
                    <td class="separator">:</td>
                    <td class="value" style="color: #4f45b2; font-family: monospace; font-size: 11pt;">{{ $student->nomor_registrasi }}</td>
                </tr>
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="separator">:</td>
                    <td class="value">{{ strtoupper($student->nama_lengkap) }}</td>
                </tr>
                <tr>
                    <td class="label">NISN</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $student->nisn }}</td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $student->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $student->tempat_lahir }}, {{ $student->tanggal_lahir?->format('d F Y') }}</td>
                </tr>
            </table>

            <!-- Photo Area (Pas Foto 3x4) -->
            <div class="photo-box">
                @if($student->foto_siswa)
                    <img src="{{ $student->fotoUrl() }}" alt="Pas Foto">
                @else
                    PAS FOTO<br>CALON SISWA<br>3 x 4
                @endif
            </div>
        </div>

        <!-- Section: Informasi Kontak & Akademik -->
        <div class="section-header">A. Informasi Kontak & Akademik</div>
        <table class="grid-table">
            <tr>
                <td class="label">Asal Sekolah (SMP/MTs)</td>
                <td class="separator">:</td>
                <td class="value">{{ $student->sekolah_asal }}</td>
            </tr>
            <tr>
                <td class="label">Ukuran Baju Olahraga</td>
                <td class="separator">:</td>
                <td class="value">Ukuran {{ $student->ukuran_baju }}</td>
            </tr>
            <tr>
                <td class="label">Nomor HP / WhatsApp</td>
                <td class="separator">:</td>
                <td class="value">{{ $student->nomor_hp }}</td>
            </tr>
            <tr>
                <td class="label">Email Aktif</td>
                <td class="separator">:</td>
                <td class="value">{{ $student->email }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Rumah Lengkap</td>
                <td class="separator">:</td>
                <td class="value">{{ $student->alamat_lengkap }}</td>
            </tr>
        </table>

        <!-- Section: Data Orang Tua -->
        <div class="section-header">B. Informasi Orang Tua / Wali</div>
        <table class="grid-table">
            <tr>
                <td class="label">Nama Lengkap Ayah</td>
                <td class="separator">:</td>
                <td class="value">{{ $student->nama_ayah }}</td>
            </tr>
            <tr>
                <td class="label">Nama Lengkap Ibu</td>
                <td class="separator">:</td>
                <td class="value">{{ $student->nama_ibu }}</td>
            </tr>
        </table>

        <!-- Section: Data Kustom Tambahan (Dynamic Fields) -->
        @if(!empty($customFields))
            <div class="section-header">C. Informasi Tambahan Calon Siswa (Kustom)</div>
            <table class="grid-table">
                @foreach($customFields as $field)
                    @php
                        $val = $student->additional_fields[$field['id']] ?? '-';
                        if (is_array($val)) {
                            $val = implode(', ', $val);
                        }
                    @endphp
                    <tr>
                        <td class="label">{{ $field['label'] }}</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $val }}</td>
                    </tr>
                @endforeach
            </table>
        @endif

        <!-- Section: Status Verifikasi -->
        <div class="section-header">D. Status Registrasi & Verifikasi</div>
        <table class="grid-table">
            <tr>
                <td class="label">Status Kelulusan PPDB</td>
                <td class="separator">:</td>
                <td class="value" style="text-transform: uppercase;">
                    @if($student->status === 'diterima')
                        DITERIMA (TERVERIFIKASI)
                    @elseif($student->status === 'ditolak')
                        DITOLAK
                    @else
                        MENUNGGU PROSES VERIFIKASI DOKUMEN
                    @endif
                </td>
            </tr>
            @if($student->status === 'ditolak' && $student->catatan_admin)
                <tr>
                    <td class="label">Catatan Tim Penilai</td>
                    <td class="separator">:</td>
                    <td class="value" style="color: #ff0000; font-style: italic;">{{ $student->catatan_admin }}</td>
                </tr>
            @endif
        </table>

        <!-- Signatures Area with Student Manual & Committee Barcode Verification -->
        <div class="signature-block">
            <!-- Left Side: Student Manual Signature -->
            <div class="signature-col" style="align-items: flex-start; text-align: left; padding-left: 20px;">
                <div class="signature-date">&nbsp;</div>
                <div class="signature-title">Calon Peserta Didik Baru,</div>
                <div style="height: 52px;"></div> <!-- Space for manual signature -->
                <div class="signature-name">{{ $student->nama_lengkap }}</div>
            </div>
            
            <!-- Right Side: Panitia Digital Signature Barcode -->
            <div class="signature-col" style="align-items: center; text-align: center;">
                <div class="signature-date">Limpung, {{ date('d F Y') }}</div>
                <div class="signature-title">Panitia PPDB MAM Limpung,</div>
                
                <!-- Digital QR Verification QR-Code (Fully functional scanned authenticity) -->
                <div class="barcode-container" style="margin: 4px auto; width: 65px; height: 65px; border: none; padding: 2px; box-sizing: border-box; background-color: #fff; display: flex; align-items: center; justify-content: center;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/ppdb/verify/' . $student->nomor_registrasi)) }}" alt="QR Verifikasi" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                
                <div class="signature-name" style="text-decoration: none; font-size: 7.5pt; font-family: monospace; color: #444; margin-top: 1px;">
                    [{{ $student->nomor_registrasi }}]
                </div>
                <div class="signature-verification" style="font-size: 7pt; font-family: sans-serif; font-style: italic; color: #15803d; font-weight: bold; margin-top: 0px; letter-spacing: 0.2px;">
                    ✓ TERVERIFIKASI ASLI
                </div>
            </div>
        </div>

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
