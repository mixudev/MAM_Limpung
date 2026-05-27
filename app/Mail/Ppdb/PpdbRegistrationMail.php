<?php

namespace App\Mail\Ppdb;

use App\Mail\BaseMail;
use App\Models\PpdbSiswa;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PpdbRegistrationMail extends BaseMail
{
    public function __construct(
        public readonly PpdbSiswa $siswa,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pendaftaran PPDB — '.$this->siswa->nama_lengkap,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ppdb.registration',
        );
    }
}
