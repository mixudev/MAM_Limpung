<?php

namespace App\Mail\User;

use App\Mail\BaseMail;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OtpLoginMail extends BaseMail
{
    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly User $user,
        public readonly string $otpCode,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Login Anda — '.$this->user->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user.otp_login',
        );
    }
}
