<?php

namespace App\Actions\Ppdb;

use App\Models\PpdbSiswa;
use InvalidArgumentException;

class RejectStudentAction
{
    /**
     * Reject registration with an obligatory reason.
     *
     * @param PpdbSiswa $siswa
     * @param string    $reason
     * @return bool
     * @throws InvalidArgumentException
     */
    public function execute(PpdbSiswa $siswa, string $reason): bool
    {
        if (empty(trim($reason))) {
            throw new InvalidArgumentException('Alasan penolakan wajib diisi.');
        }

        return $siswa->update([
            'status'        => 'ditolak',
            'catatan_admin' => trim($reason),
        ]);
    }
}
