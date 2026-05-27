@extends('emails.layouts.base')

@section('badge', 'Konfirmasi Pendaftaran PPDB')

@section('content')
    <h1 class="email-title">Pendaftaran Anda Diterima!</h1>
    <p class="email-subtitle">Terima kasih telah mendaftar ke MAM Limpung. Berikut adalah detail pendaftaran Anda.</p>

    <div class="info-card">
        <span class="info-card-label">Nomor Registrasi</span>
        <span class="info-card-value">{{ $siswa->nomor_registrasi }}</span>
    </div>

    <table class="data-grid">
        <tr>
            <td>Nama Lengkap</td>
            <td>{{ $siswa->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>NISN</td>
            <td>{{ $siswa->nisn ?: '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td>Sekolah Asal</td>
            <td>{{ $siswa->sekolah_asal ?: '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal Daftar</td>
            <td>{{ $siswa->submitted_at ? $siswa->submitted_at->format('d F Y, H:i') : '-' }}</td>
        </tr>
        <tr>
            <td>Status Saat Ini</td>
            <td>
                @php
                    $statusClass = match($siswa->status) {
                        'diterima' => 'status-diterima',
                        'ditolak'  => 'status-ditolak',
                        default    => 'status-pending',
                    };
                    $statusLabel = match($siswa->status) {
                        'diterima' => 'Diterima',
                        'ditolak'  => 'Tidak Diterima',
                        default    => 'Dalam Proses',
                    };
                @endphp
                <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
            </td>
        </tr>
    </table>

    <div class="email-divider"></div>

    <div class="alert-box alert-info">
        <strong>Langkah Selanjutnya:</strong> Tim kami akan memverifikasi berkas dan dokumen pendaftaran Anda. Anda akan menerima notifikasi email kembali apabila terdapat perubahan status pendaftaran.
    </div>

    <p style="font-size: 13px; color: #475569; margin: 0;">
        Jika Anda memiliki pertanyaan, hubungi kami melalui WhatsApp atau kunjungi langsung kantor madrasah kami.
    </p>
@endsection
