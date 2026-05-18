<?php

namespace App\Http\Controllers\Dashboard\Ppdb;

use App\Actions\Ppdb\RejectStudentAction;
use App\Actions\Ppdb\VerifyStudentAction;
use App\Http\Controllers\Controller;
use App\Models\PpdbSiswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminPpdbVerificationController extends Controller
{
    /**
     * Verify and accept an applicant.
     */
    public function verify(Request $request, PpdbSiswa $ppdbSiswa, VerifyStudentAction $action): RedirectResponse
    {
        $action->execute($ppdbSiswa, $request->input('catatan_admin'));

        return redirect()->back()->with(
            'success',
            'Verifikasi Berhasil!|Calon siswa '.$ppdbSiswa->nama_lengkap.' berhasil diverifikasi.'
        );
    }

    /**
     * Reject an applicant with a custom feedback message.
     */
    public function reject(Request $request, PpdbSiswa $ppdbSiswa, RejectStudentAction $action): RedirectResponse
    {
        $request->validate([
            'catatan_admin' => ['required', 'string', 'min:5'],
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi.',
            'catatan_admin.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $action->execute($ppdbSiswa, $request->input('catatan_admin'));

        return redirect()->back()->with(
            'success',
            'Penolakan Berhasil!|Pendaftaran calon siswa '.$ppdbSiswa->nama_lengkap.' berhasil ditolak.'
        );
    }
}
