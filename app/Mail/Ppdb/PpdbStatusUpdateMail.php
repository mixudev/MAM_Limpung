<?php

namespace App\Mail\Ppdb;

use App\Mail\BaseMail;
use App\Models\PpdbSiswa;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PpdbStatusUpdateMail extends BaseMail
{
    public function __construct(
        public readonly PpdbSiswa $siswa,
        public readonly string $previousStatus,
    ) {}

    public function envelope(): Envelope
    {
        $statusLabel = match ($this->siswa->status) {
            'diterima' => 'Diterima',
            'ditolak' => 'Tidak Diterima',
            default => 'Dalam Proses',
        };

        return new Envelope(
            subject: "Update Status PPDB: {$statusLabel} — ".$this->siswa->nama_lengkap,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ppdb.status_update',
        );
    }
}
