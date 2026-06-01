@extends('emails.layouts.base')

@section('badge', 'Verifikasi Login')

@section('content')
    <h1 class="email-title">Kode Keamanan OTP Anda</h1>
    <p class="email-subtitle">Halo <strong>{{ $user->name }}</strong>, gunakan kode OTP berikut untuk menyelesaikan proses masuk ke akun Anda.</p>

    <div style="text-align: center; margin: 35px 0;">
        <span style="font-size: 32px; font-family: monospace; letter-spacing: 8px; font-weight: bold; background-color: #f1f5f9; color: #4f45b2; padding: 12px 24px; border: 1px dashed #cbd5e1; border-radius: 4px; display: inline-block;">{{ $otpCode }}</span>
    </div>

    <div class="alert-box alert-warning">
        Kode keamanan OTP ini berlaku selama <strong>5 menit</strong>. Jangan bagikan kode ini kepada siapa pun, termasuk pihak sekolah. Petugas sekolah tidak pernah meminta kode OTP Anda.
    </div>

    <div class="email-divider"></div>

    <p style="font-size: 13px; color: #475569; margin: 0;">
        Jika Anda tidak merasa mencoba masuk ke akun Anda, abaikan email ini atau hubungi administrator untuk memastikan keamanan akun Anda.
    </p>
@endsection
