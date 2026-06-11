<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\PpdbStoreRequest;
use App\Jobs\SyncPpdbToGoogleSheetsJob;
use App\Mail\Ppdb\PpdbRegistrationMail;
use App\Models\PpdbSetting;
use App\Models\PpdbSiswa;
use App\Services\SmtpService;
use App\Support\PpdbTempUploadManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class PpdbController extends Controller
{
    public function index(): View
    {
        $general = PpdbSetting::getValue('general', [
            'is_open' => true,
            'tahun_ajaran' => (int) date('Y'),
        ]);
        $waves = collect(PpdbSetting::getValue('waves', []))
            ->filter(function ($wave) {
                return $wave['is_active'] ?? true;
            })
            ->values()
            ->all();

        $requirements = collect(PpdbSetting::getValue('requirements', []))->filter(function ($req) {
            return $req['is_active'] ?? true;
        })->values()->all();

        return view('front.ppdb.index', compact('general', 'waves', 'requirements'));
    }

    public function form(): View|RedirectResponse
    {
        $general = PpdbSetting::getValue('general', [
            'is_open' => true,
            'tahun_ajaran' => (int) date('Y'),
        ]);
        $waves = PpdbSetting::getValue('waves', []);

        $today = date('Y-m-d');
        $isWaveActive = false;

        foreach ($waves as $wave) {
            if ($today >= $wave['start_date'] && $today <= $wave['end_date']) {
                $isWaveActive = true;
                break;
            }
        }

        // If waves are empty, we just fallback to general is_open
        $isOpen = $general['is_open'] && (empty($waves) || $isWaveActive);

        if (! $isOpen) {
            return view('front.ppdb.closed', compact('general', 'waves'));
        }

        $formFields = collect(PpdbSetting::getValue('form_fields', []))->filter(function ($field) {
            return $field['is_active'] ?? true;
        })->values()->all();

        $requirements = collect(PpdbSetting::getValue('requirements', []))->filter(function ($req) {
            return $req['is_active'] ?? true;
        })->values()->all();

        $ppdbTempUploads = PpdbTempUploadManager::forView();

        return view('front.ppdb.form', compact('general', 'waves', 'formFields', 'requirements', 'ppdbTempUploads'));
    }

    public function store(PpdbStoreRequest $request): RedirectResponse
    {
        $general = PpdbSetting::getValue('general', [
            'is_open' => true,
            'tahun_ajaran' => (int) date('Y'),
        ]);
        $waves = PpdbSetting::getValue('waves', []);

        $today = date('Y-m-d');
        $isWaveActive = false;

        foreach ($waves as $wave) {
            if ($today >= $wave['start_date'] && $today <= $wave['end_date']) {
                $isWaveActive = true;
                break;
            }
        }

        $isOpen = $general['is_open'] && (empty($waves) || $isWaveActive);

        if (! $isOpen) {
            return redirect()->route('frontend.ppdb.index')
                ->with('error', 'Mohon maaf, pendaftaran PPDB saat ini sedang ditutup.');
        }

        $validated = $request->validated();

        // 1. Process main student photo upload (file baru atau dari sesi sementara)
        if ($request->hasFile('foto_siswa')) {
            $file = $request->file('foto_siswa');
            $filename = 'ppdb_'.uniqid().'.'.$file->getClientOriginalExtension();
            $validated['foto_siswa'] = $file->storeAs('ppdb/photos', $filename, 'public');
        } elseif ($tempFoto = PpdbTempUploadManager::take('foto_siswa', 'ppdb/photos', 'ppdb')) {
            $validated['foto_siswa'] = $tempFoto;
        }

        // 2. Package dynamic requirements uploads & custom form fields into additional_fields JSON
        $additional = [];

        // Handle dynamic requirement document uploads (except main photo which is 'foto')
        $requirements = PpdbSetting::getValue('requirements', []);
        foreach ($requirements as $req) {
            if ($req['id'] === 'foto' || ! ($req['is_active'] ?? true)) {
                continue;
            }
            if ($request->hasFile($req['id'])) {
                $file = $request->file($req['id']);
                $filename = 'req_'.$req['id'].'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $path = $file->storeAs('ppdb/requirements', $filename, 'public');
                $additional[$req['id']] = $path;
                unset($validated[$req['id']]);
            } elseif ($tempPath = PpdbTempUploadManager::take($req['id'], 'ppdb/requirements', 'req_'.$req['id'])) {
                $additional[$req['id']] = $tempPath;
                unset($validated[$req['id']]);
            }
        }

        // Handle custom input fields
        $formFields = PpdbSetting::getValue('form_fields', []);
        foreach ($formFields as $field) {
            if (! ($field['is_active'] ?? true)) {
                continue;
            }
            if (isset($validated[$field['id']])) {
                $additional[$field['id']] = $validated[$field['id']];
                unset($validated[$field['id']]);
            }
        }

        $validated['additional_fields'] = $additional;

        $ppdbSiswa = PpdbSiswa::create($validated);

        PpdbTempUploadManager::clear();

        // Kirim email konfirmasi pendaftaran secara senyap
        app(SmtpService::class)->sendQuiet(
            new PpdbRegistrationMail($ppdbSiswa),
            $ppdbSiswa->email,
            $ppdbSiswa->nama_lengkap
        );

        // Sinkronisasi otomatis ke Google Sheets via background job
        SyncPpdbToGoogleSheetsJob::dispatch($ppdbSiswa);

        return redirect()->to(
            URL::signedRoute('frontend.ppdb.success', ['ppdbSiswa' => $ppdbSiswa->nomor_registrasi])
        )->with('success', 'Pendaftaran berhasil! Simpan nomor pendaftaran Anda.');
    }

    public function success(PpdbSiswa $ppdbSiswa): View
    {
        $general = PpdbSetting::getValue('general', [
            'tahun_ajaran' => (int) date('Y'),
        ]);
        $customFields = PpdbSetting::getValue('form_fields', []);

        $printDocumentUrl = URL::signedRoute('frontend.ppdb.success', [
            'ppdbSiswa' => $ppdbSiswa->nomor_registrasi,
            'print' => 1,
            'embed' => 1,
        ]);

        if (request()->boolean('print') && request()->boolean('embed')) {
            return view('front.ppdb.print-bukti', [
                'student' => $ppdbSiswa,
                'general' => $general,
                'customFields' => $customFields,
            ]);
        }

        return view('front.ppdb.success', [
            'ppdb_siswa' => $ppdbSiswa,
            'general' => $general,
            'printDocumentUrl' => $printDocumentUrl,
        ]);
    }

    public function verify(string $nomor_registrasi): View
    {
        $ppdbSiswa = PpdbSiswa::where('nomor_registrasi', $nomor_registrasi)->firstOrFail();

        return view('front.ppdb.verify', [
            'ppdb_siswa' => $ppdbSiswa,
        ]);
    }

    public function statusForm(): View
    {
        return view('front.ppdb.status');
    }

    public function checkStatus(Request $request): View|RedirectResponse
    {
        $request->validate([
            'keyword' => ['required', 'string', 'min:3', 'max:100'],
        ], [
            'keyword.required' => 'Kata kunci pencarian wajib diisi.',
            'keyword.min' => 'Kata kunci minimal 3 karakter.',
        ]);

        $keyword = trim($request->input('keyword'));
        $keywordUpper = strtoupper($keyword);

        // Cari berdasarkan nomor registrasi, NISN, atau nama lengkap
        $ppdbSiswa = PpdbSiswa::where('nomor_registrasi', $keywordUpper)
            ->orWhere('nisn', $keyword)
            ->orWhere('nama_lengkap', 'like', '%'.$keyword.'%')
            ->first();

        if (! $ppdbSiswa) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Data tidak ditemukan. Coba masukkan nomor pendaftaran, NISN, atau nama lengkap Anda.']);
        }

        // Hasilkan signed URL untuk cetak kartu pendaftaran dinamis
        $printDocumentUrl = URL::signedRoute('frontend.ppdb.success', [
            'ppdbSiswa' => $ppdbSiswa->nomor_registrasi,
            'print' => 1,
            'embed' => 1,
        ]);

        return view('front.ppdb.status', [
            'ppdb_siswa' => $ppdbSiswa,
            'printDocumentUrl' => $printDocumentUrl,
        ]);
    }
}
