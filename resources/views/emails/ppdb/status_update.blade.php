@extends('emails.layouts.base')

@section('badge', 'Update Status PPDB')

@section('content')
    @php
        $statusClass = match($siswa->status) {
            'diterima' => 'status-diterima',
            'ditolak'  => 'status-ditolak',
            default    => 'status-pending',
        };
        $statusLabel = match($siswa->status) {
            'diterima' => 'DITERIMA',
            'ditolak'  => 'TIDAK DITERIMA',
            default    => 'DALAM PROSES',
        };
        $alertClass = match($siswa->status) {
            'diterima' => 'alert-success',
            'ditolak'  => 'alert-warning',
            default    => 'alert-info',
        };
        $alertMsg = match($siswa->status) {
            'diterima' => 'Selamat! Anda telah dinyatakan DITERIMA di MAM Limpung. Segera lakukan proses daftar ulang sesuai jadwal yang ditentukan.',
            'ditolak'  => 'Kami mohon maaf, pendaftaran Anda tidak dapat kami terima pada periode ini. Anda dapat menghubungi kami untuk informasi lebih lanjut.',
            default    => 'Pendaftaran Anda sedang dalam proses verifikasi oleh tim kami. Mohon tunggu pemberitahuan selanjutnya.',
        };
    @endphp

    <h1 class="email-title">Status Pendaftaran Diperbarui</h1>
    <p class="email-subtitle">
        Halo <strong>{{ $siswa->nama_lengkap }}</strong>, terdapat pembaruan status pada pendaftaran PPDB Anda.
    </p>

    <div style="display: flex; align-items: center; gap: 16px; padding: 20px; background: #f8fafc; border: 1px solid #e2e8f0; margin: 0 0 20px 0;">
        <div style="flex: 1;">
            <span style="font-size: 10px; font-family: 'Courier New', monospace; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; display: block; margin-bottom: 4px;">Status Terbaru</span>
            <span class="status-badge {{ $statusClass }}" style="font-size: 13px; padding: 5px 14px;">{{ $statusLabel }}</span>
        </div>
        <div style="text-align: right;">
            <span style="font-size: 10px; font-family: 'Courier New', monospace; color: #94a3b8; display: block;">No. Registrasi</span>
            <span style="font-size: 14px; font-weight: 700; font-family: 'Courier New', monospace; color: #0f172a;">{{ $siswa->nomor_registrasi }}</span>
        </div>
    </div>

    <div class="alert-box {{ $alertClass }}">
        {{ $alertMsg }}
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
            <td>Sekolah Asal</td>
            <td>{{ $siswa->sekolah_asal ?: '-' }}</td>
        </tr>
        <tr>
            <td>No. HP / WA</td>
            <td>{{ $siswa->nomor_hp ?: '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal Update</td>
            <td>{{ now()->format('d F Y, H:i') }} WIB</td>
        </tr>
    </table>

    <div class="email-divider"></div>

    <p style="font-size: 13px; color: #475569; margin: 0;">
        Jika Anda memiliki pertanyaan terkait status ini, silakan hubungi kami. Harap menyertakan nomor registrasi Anda saat menghubungi pihak madrasah.
    </p>
@endsection
