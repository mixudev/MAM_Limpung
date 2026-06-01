<?php

namespace App\Mail\User;

use App\Mail\BaseMail;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ForgotPasswordMail extends BaseMail
{
    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly User $user,
        public readonly string $resetUrl,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tautan Atur Ulang Kata Sandi Anda — '.$this->user->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user.forgot_password',
        );
    }
}
