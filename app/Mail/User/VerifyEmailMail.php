<?php

namespace App\Mail\User;

use App\Mail\BaseMail;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class VerifyEmailMail extends BaseMail
{
    public function __construct(
        public readonly User $user,
        public readonly string $verificationUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verifikasi Alamat Email Anda — '.$this->user->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.verify_email',
        );
    }
}
