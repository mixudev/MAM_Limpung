<?php

namespace App\Http\Controllers\Dashboard\Ppdb;

use App\Actions\Ppdb\RejectStudentAction;
use App\Actions\Ppdb\VerifyStudentAction;
use App\Http\Controllers\Controller;
use App\Jobs\SyncPpdbToGoogleSheetsJob;
use App\Mail\Ppdb\PpdbStatusUpdateMail;
use App\Models\PpdbSiswa;
use App\Services\SmtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminPpdbVerificationController extends Controller
{
    /**
     * Verify and accept an applicant.
     */
    public function verify(Request $request, PpdbSiswa $ppdbSiswa, VerifyStudentAction $action): RedirectResponse
    {
        $previousStatus = $ppdbSiswa->status;

        $action->execute($ppdbSiswa, $request->input('catatan_admin'));

        // Kirim email status update secara senyap
        app(SmtpService::class)->sendQuiet(
            new PpdbStatusUpdateMail($ppdbSiswa, $previousStatus),
            $ppdbSiswa->email,
            $ppdbSiswa->nama_lengkap
        );

        // Sinkronisasi otomatis ke Google Sheets via background job
        SyncPpdbToGoogleSheetsJob::dispatch($ppdbSiswa);

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

        $previousStatus = $ppdbSiswa->status;

        $action->execute($ppdbSiswa, $request->input('catatan_admin'));

        // Kirim email status update secara senyap
        app(SmtpService::class)->sendQuiet(
            new PpdbStatusUpdateMail($ppdbSiswa, $previousStatus),
            $ppdbSiswa->email,
            $ppdbSiswa->nama_lengkap
        );

        // Sinkronisasi otomatis ke Google Sheets via background job
        SyncPpdbToGoogleSheetsJob::dispatch($ppdbSiswa);

        return redirect()->back()->with(
            'success',
            'Penolakan Berhasil!|Pendaftaran calon siswa '.$ppdbSiswa->nama_lengkap.' berhasil ditolak.'
        );
    }
}
