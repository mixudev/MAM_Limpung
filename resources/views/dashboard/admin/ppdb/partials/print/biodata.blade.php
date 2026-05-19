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
