@extends('emails.layouts.base')

@section('badge', 'Verifikasi Akun')

@section('content')
    <h1 class="email-title">Verifikasi Alamat Email Anda</h1>
    <p class="email-subtitle">Halo <strong>{{ $user->name }}</strong>, silakan verifikasi alamat email Anda agar dapat mengakses sistem secara penuh.</p>

    <div class="alert-box alert-info">
        Akun Anda telah terdaftar di sistem MAM Limpung. Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda. Tautan verifikasi ini berlaku selama 3 hari.
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $verificationUrl }}" class="cta-btn" style="background-color: #4f45b2; color: #ffffff !important; padding: 12px 30px; text-decoration: none; font-weight: bold; border-radius: 4px; display: inline-block;">Verifikasi Email Sekarang</a>
    </div>

    <p style="font-size: 11px; color: #64748b; margin-top: 24px; line-height: 1.5;">
        Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:<br>
        <a href="{{ $verificationUrl }}" style="word-break: break-all; color: #4f45b2;">{{ $verificationUrl }}</a>
    </p>

    <div class="email-divider"></div>

    <p style="font-size: 13px; color: #475569; margin: 0;">
        Jika Anda tidak merasa mendaftar atau merasa ini adalah kesalahan, Anda dapat mengabaikan email ini dengan aman.
    </p>
@endsection
