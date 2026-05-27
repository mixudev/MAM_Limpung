<?php

namespace App\Mail\User;

use App\Mail\BaseMail;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ResetPasswordMail extends BaseMail
{
    public function __construct(
        public readonly User $user,
        public readonly string $resetUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tautan Reset Kata Sandi Anda — '.$this->user->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.reset_password',
        );
    }
}
