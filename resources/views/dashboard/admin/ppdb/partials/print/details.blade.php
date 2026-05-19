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
