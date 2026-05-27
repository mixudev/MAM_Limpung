@extends('emails.layouts.base')

@section('badge', 'Uji Koneksi SMTP')

@section('content')
    <h1 class="email-title">🎉 Koneksi SMTP Berhasil!</h1>
    <p class="email-subtitle">Email pengujian ini dikirim secara otomatis dari sistem MAM Limpung untuk memverifikasi bahwa konfigurasi SMTP Anda berfungsi dengan baik.</p>

    <div class="alert-box alert-success">
        <strong>Selamat!</strong> Konfigurasi SMTP Anda sudah benar dan berhasil terhubung. Sistem email notifikasi MAM Limpung siap digunakan.
    </div>

    <div class="info-card">
        <span class="info-card-label">Waktu Pengujian</span>
        <span class="info-card-value">{{ now()->format('d F Y, H:i:s') }} WIB</span>
    </div>

    <div class="email-divider"></div>

    <p style="font-size: 13px; color: #475569; margin: 0 0 8px 0; font-weight: 600;">Yang bisa dilakukan selanjutnya:</p>
    <ul style="font-size: 13px; color: #475569; margin: 0; padding-left: 20px; line-height: 2;">
        <li>Sistem siap mengirim konfirmasi pendaftaran PPDB secara otomatis</li>
        <li>Notifikasi perubahan status pendaftar akan dikirim via email</li>
        <li>Semua notifikasi menggunakan template yang profesional & branded</li>
    </ul>

    <div class="email-divider"></div>

    <p style="font-size: 12px; color: #94a3b8; margin: 0;">
        Email ini dihasilkan oleh fitur "Uji Koneksi SMTP" di panel admin. Jika Anda tidak mengaktifkan fitur ini, abaikan email ini.
    </p>
@endsection
