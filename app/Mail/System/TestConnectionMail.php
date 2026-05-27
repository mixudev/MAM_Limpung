<?php

namespace App\Mail\System;

use App\Mail\BaseMail;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TestConnectionMail extends BaseMail
{
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[MAM Limpung] Uji Koneksi SMTP — Berhasil!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.system.test',
        );
    }
}
