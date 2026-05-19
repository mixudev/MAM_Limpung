<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\PpdbStoreRequest;
use App\Jobs\SyncPpdbToGoogleSheetsJob;
use App\Models\PpdbSetting;
use App\Models\PpdbSiswa;
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
            'target_quota' => 120,
            'registration_fee' => 150000,
            'start_date' => date('Y').'-05-01',
            'end_date' => date('Y').'-08-31',
        ]);
        $requirements = PpdbSetting::getValue('requirements', []);

        return view('front.ppdb.index', compact('general', 'requirements'));
    }

    public function form(): View|RedirectResponse
    {
        $general = PpdbSetting::getValue('general', [
            'is_open' => true,
            'tahun_ajaran' => (int) date('Y'),
            'target_quota' => 120,
            'registration_fee' => 150000,
            'start_date' => date('Y').'-05-01',
            'end_date' => date('Y').'-08-31',
        ]);

        $today = date('Y-m-d');
        $isOpen = $general['is_open'] && ($today >= $general['start_date'] && $today <= $general['end_date']);

        if (! $isOpen) {
            return view('front.ppdb.closed', compact('general'));
        }

        $formFields = PpdbSetting::getValue('form_fields', []);
        $requirements = PpdbSetting::getValue('requirements', []);

        return view('front.ppdb.form', compact('general', 'formFields', 'requirements'));
    }

    public function store(PpdbStoreRequest $request): RedirectResponse
    {
        $general = PpdbSetting::getValue('general', [
            'is_open' => true,
            'tahun_ajaran' => (int) date('Y'),
            'target_quota' => 120,
            'registration_fee' => 150000,
            'start_date' => date('Y').'-05-01',
            'end_date' => date('Y').'-08-31',
        ]);

        $today = date('Y-m-d');
        $isOpen = $general['is_open'] && ($today >= $general['start_date'] && $today <= $general['end_date']);

        if (! $isOpen) {
            return redirect()->route('frontend.ppdb.index')
                ->with('error', 'Mohon maaf, pendaftaran PPDB saat ini sedang ditutup.');
        }

        $validated = $request->validated();

        // 1. Process main student photo upload
        if ($request->hasFile('foto_siswa')) {
            $file = $request->file('foto_siswa');
            $filename = 'ppdb_'.uniqid().'.'.$file->getClientOriginalExtension();
            $validated['foto_siswa'] = $file->storeAs('ppdb/photos', $filename, 'public');
        }

        // 2. Package dynamic requirements uploads & custom form fields into additional_fields JSON
        $additional = [];

        // Handle dynamic requirement document uploads (except main photo which is 'foto')
        $requirements = PpdbSetting::getValue('requirements', []);
        foreach ($requirements as $req) {
            if ($req['id'] === 'foto') {
                continue;
            }
            if ($request->hasFile($req['id'])) {
                $file = $request->file($req['id']);
                $filename = 'req_'.$req['id'].'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $path = $file->storeAs('ppdb/requirements', $filename, 'public');
                $additional[$req['id']] = $path;
                unset($validated[$req['id']]);
            }
        }

        // Handle custom input fields
        $formFields = PpdbSetting::getValue('form_fields', []);
        foreach ($formFields as $field) {
            if (isset($validated[$field['id']])) {
                $additional[$field['id']] = $validated[$field['id']];
                unset($validated[$field['id']]);
            }
        }

        $validated['additional_fields'] = $additional;

        $ppdbSiswa = PpdbSiswa::create($validated);

        // Sinkronisasi otomatis ke Google Sheets via background job
        SyncPpdbToGoogleSheetsJob::dispatch($ppdbSiswa);

        return redirect()->to(
            URL::signedRoute('frontend.ppdb.success', ['ppdbSiswa' => $ppdbSiswa->nomor_registrasi])
        )->with('success', 'Pendaftaran berhasil! Simpan nomor pendaftaran Anda.');
    }

    public function success(PpdbSiswa $ppdbSiswa): View
    {
        return view('front.ppdb.success', [
            'ppdb_siswa' => $ppdbSiswa,
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
            'nomor_registrasi' => ['required', 'string'],
            'nisn' => ['required', 'string', 'digits:10'],
        ], [
            'nomor_registrasi.required' => 'Nomor pendaftaran wajib diisi.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.digits' => 'NISN harus berupa 10 digit angka.',
        ]);

        $nomorRegistrasi = strtoupper(trim($request->input('nomor_registrasi')));
        $nisn = trim($request->input('nisn'));

        // Cocokkan Nomor Registrasi DAN NISN secara presisi untuk keamanan penuh
        $ppdbSiswa = PpdbSiswa::where('nomor_registrasi', $nomorRegistrasi)
            ->where('nisn', $nisn)
            ->first();

        if (! $ppdbSiswa) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Nomor pendaftaran atau NISN tidak cocok / tidak ditemukan di sistem kami. Silakan periksa kembali data Anda.']);
        }

        // Hasilkan signed URL untuk cetak kartu pendaftaran dinamis
        $printUrl = URL::signedRoute('frontend.ppdb.success', ['ppdbSiswa' => $ppdbSiswa->nomor_registrasi, 'print' => 1]);

        return view('front.ppdb.status', [
            'ppdb_siswa' => $ppdbSiswa,
            'print_url' => $printUrl,
        ]);
    }
}
