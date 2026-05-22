<?php

namespace App\Actions\Ppdb;

use App\Models\PpdbSiswa;

class VerifyStudentAction
{
    /**
     * Approve registration and mark status as accepted.
     */
    public function execute(PpdbSiswa $siswa, ?string $note = null): bool
    {
        return $siswa->update([
            'status' => 'diterima',
            'catatan_admin' => $note ?? 'Pendaftaran terverifikasi dan berkas dinyatakan lengkap.',
        ]);
    }
}
