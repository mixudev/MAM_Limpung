<?php

namespace App\Http\Controllers\Dashboard\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PpdbSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPpdbSettingController extends Controller
{
    /**
     * Display the settings workspace.
     */
    public function edit(): View
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
        $formFields = PpdbSetting::getValue('form_fields', []);

        return view('dashboard.admin.ppdb.settings', compact('general', 'requirements', 'formFields'));
    }

    /**
     * Update the general PPDB settings.
     */
    public function updateGeneral(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'is_open' => 'required|boolean',
            'tahun_ajaran' => 'required|integer|min:2020|max:2100',
            'target_quota' => 'required|integer|min:1|max:5000',
            'registration_fee' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        PpdbSetting::setValue('general', [
            'is_open' => (bool) $validated['is_open'],
            'tahun_ajaran' => (int) $validated['tahun_ajaran'],
            'target_quota' => (int) $validated['target_quota'],
            'registration_fee' => (int) $validated['registration_fee'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return redirect()->route('admin.ppdb.settings.edit')
            ->with('success', 'Konfigurasi PPDB|Pengaturan umum berhasil diperbarui.');
    }

    /**
     * Update requirements list.
     */
    public function updateRequirements(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'requirements' => 'nullable|array',
            'requirements.*.id' => 'required|string|alpha_dash',
            'requirements.*.label' => 'required|string|max:100',
            'requirements.*.required' => 'required|boolean',
        ]);

        $reqList = [];
        if (! empty($validated['requirements'])) {
            foreach ($validated['requirements'] as $req) {
                $reqList[] = [
                    'id' => Str::slug($req['id'], '_'),
                    'label' => strip_tags($req['label']),
                    'required' => (bool) $req['required'],
                ];
            }
        }

        PpdbSetting::setValue('requirements', $reqList);

        return redirect()->route('admin.ppdb.settings.edit')
            ->with('success', 'Persyaratan Dokumen|Daftar berkas wajib berhasil disimpan.');
    }

    /**
     * Add a custom form input field.
     */
    public function storeField(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'type' => 'required|string|in:text,number,select,date,textarea',
            'options' => 'nullable|string',
            'required' => 'required|boolean',
        ]);

        $id = Str::slug($validated['label'], '_');
        if (empty($id)) {
            return redirect()->back()->withErrors(['label' => 'Label input tidak valid.']);
        }

        // Prevent collisions with system core fields
        $reserved = [
            'nama_lengkap', 'nisn', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'nomor_hp', 'email', 'nama_ayah', 'nama_ibu', 'alamat_lengkap', 'sekolah_asal',
            'ukuran_baju', 'foto_siswa', 'status', 'catatan_admin', 'submitted_at',
        ];
        if (in_array($id, $reserved)) {
            return redirect()->back()->withErrors(['label' => 'Label ini bertabrakan dengan kolom sistem bawaan.']);
        }

        $fields = PpdbSetting::getValue('form_fields', []);

        // Check for duplicates
        foreach ($fields as $field) {
            if ($field['id'] === $id) {
                return redirect()->back()->withErrors(['label' => 'Kolom input dengan label serupa sudah terdaftar.']);
            }
        }

        // Process dropdown options
        $optionsArr = [];
        if ($validated['type'] === 'select' && ! empty($validated['options'])) {
            $optionsArr = array_filter(array_map('trim', explode(',', $validated['options'])));
        }

        $fields[] = [
            'id' => $id,
            'label' => strip_tags($validated['label']),
            'type' => $validated['type'],
            'options' => $optionsArr,
            'required' => (bool) $validated['required'],
        ];

        PpdbSetting::setValue('form_fields', $fields);

        return redirect()->route('admin.ppdb.settings.edit')
            ->with('success', 'Pembangun Formulir|Kolom input baru berhasil ditambahkan.');
    }

    /**
     * Delete a custom form input field.
     */
    public function destroyField(string $fieldId): RedirectResponse
    {
        $fields = PpdbSetting::getValue('form_fields', []);

        $newFields = array_values(array_filter($fields, function ($field) use ($fieldId) {
            return $field['id'] !== $fieldId;
        }));

        PpdbSetting::setValue('form_fields', $newFields);

        return redirect()->route('admin.ppdb.settings.edit')
            ->with('success', 'Pembangun Formulir|Kolom input kustom berhasil dihapus.');
    }
}
