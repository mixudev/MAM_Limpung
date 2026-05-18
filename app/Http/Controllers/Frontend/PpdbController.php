<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\PpdbStoreRequest;
use App\Models\PpdbSiswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PpdbController extends Controller
{
    public function index(): View
    {
        return view('front.ppdb.index');
    }

    public function form(): View
    {
        return view('front.ppdb.form');
    }

    public function store(PpdbStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Upload foto dengan nama yang aman (tidak memakai nama file asli dari user)
        if ($request->hasFile('foto_siswa')) {
            $file     = $request->file('foto_siswa');
            $filename = 'ppdb_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $validated['foto_siswa'] = $file->storeAs('ppdb/photos', $filename, 'public');
        }

        $ppdbSiswa = PpdbSiswa::create($validated);

        return redirect()->to(
            \Illuminate\Support\Facades\URL::signedRoute('frontend.ppdb.success', ['ppdbSiswa' => $ppdbSiswa->nomor_registrasi])
        )->with('success', 'Pendaftaran berhasil! Simpan nomor pendaftaran Anda.');
    }

    public function success(PpdbSiswa $ppdbSiswa): View
    {
        return view('front.ppdb.success', [
            'ppdb_siswa' => $ppdbSiswa
        ]);
    }

    public function verify(string $nomor_registrasi): View
    {
        $ppdbSiswa = PpdbSiswa::where('nomor_registrasi', $nomor_registrasi)->firstOrFail();

        return view('front.ppdb.verify', [
            'ppdb_siswa' => $ppdbSiswa
        ]);
    }

    public function statusForm(): View
    {
        return view('front.ppdb.status');
    }

    public function checkStatus(\Illuminate\Http\Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'nomor_registrasi' => ['required', 'string'],
            'nisn'             => ['required', 'string', 'digits:10'],
        ], [
            'nomor_registrasi.required' => 'Nomor pendaftaran wajib diisi.',
            'nisn.required'             => 'NISN wajib diisi.',
            'nisn.digits'               => 'NISN harus berupa 10 digit angka.',
        ]);

        $nomorRegistrasi = strtoupper(trim($request->input('nomor_registrasi')));
        $nisn            = trim($request->input('nisn'));

        // Cocokkan Nomor Registrasi DAN NISN secara presisi untuk keamanan penuh
        $ppdbSiswa = PpdbSiswa::where('nomor_registrasi', $nomorRegistrasi)
            ->where('nisn', $nisn)
            ->first();

        if (!$ppdbSiswa) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Nomor pendaftaran atau NISN tidak cocok / tidak ditemukan di sistem kami. Silakan periksa kembali data Anda.']);
        }

        // Hasilkan signed URL untuk cetak kartu pendaftaran dinamis
        $printUrl = \Illuminate\Support\Facades\URL::signedRoute('frontend.ppdb.success', ['ppdbSiswa' => $ppdbSiswa->nomor_registrasi, 'print' => 1]);

        return view('front.ppdb.status', [
            'ppdb_siswa' => $ppdbSiswa,
            'print_url'  => $printUrl
        ]);
    }
}
