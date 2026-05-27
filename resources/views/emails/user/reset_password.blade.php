@extends('emails.layouts.base')

@section('badge', 'Reset Kata Sandi')

@section('content')
    <h1 class="email-title">Permintaan Reset Kata Sandi</h1>
    <p class="email-subtitle">Halo <strong>{{ $user->name }}</strong>, kami menerima permintaan untuk mereset kata sandi akun Anda.</p>

    <div class="alert-box alert-warning">
        Silakan klik tombol di bawah ini untuk membuat kata sandi baru. Tautan reset ini bersifat sekali pakai dan hanya berlaku selama 2 jam demi keamanan akun Anda.
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $resetUrl }}" class="cta-btn" style="background-color: #4f45b2; color: #ffffff !important; padding: 12px 30px; text-decoration: none; font-weight: bold; border-radius: 4px; display: inline-block;">Reset Kata Sandi Sekarang</a>
    </div>

    <p style="font-size: 11px; color: #64748b; margin-top: 24px; line-height: 1.5;">
        Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:<br>
        <a href="{{ $resetUrl }}" style="word-break: break-all; color: #4f45b2;">{{ $resetUrl }}</a>
    </p>

    <div class="email-divider"></div>

    <p style="font-size: 13px; color: #475569; margin: 0;">
        Jika Anda tidak meminta pengaturan ulang kata sandi, abaikan email ini. Kata sandi Anda akan tetap aman dan tidak berubah.
    </p>
@endsection
