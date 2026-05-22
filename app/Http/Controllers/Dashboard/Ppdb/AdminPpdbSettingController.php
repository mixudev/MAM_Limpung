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
        ]);

        $waves = PpdbSetting::getValue('waves', []);
        $requirements = PpdbSetting::getValue('requirements', []);
        $formFields = PpdbSetting::getValue('form_fields', []);

        return view('dashboard.admin.ppdb.settings', compact('general', 'waves', 'requirements', 'formFields'));
    }

    /**
     * Update the general PPDB settings.
     */
    public function updateGeneral(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'is_open' => 'required|boolean',
            'tahun_ajaran' => 'required|integer|min:2020|max:2100',
        ]);

        PpdbSetting::setValue('general', [
            'is_open' => (bool) $validated['is_open'],
            'tahun_ajaran' => (int) $validated['tahun_ajaran'],
        ]);

        return redirect()->route('admin.ppdb.settings.edit')
            ->with('success', 'Konfigurasi PPDB|Pengaturan umum berhasil diperbarui.');
    }

    /**
     * Update waves list.
     */
    public function updateWaves(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'waves' => 'nullable|array',
            'waves.*.id' => 'required|string|alpha_dash',
            'waves.*.name' => 'required|string|max:100',
            'waves.*.start_date' => 'required|date',
            'waves.*.end_date' => 'required|date|after_or_equal:waves.*.start_date',
        ]);

        $wavesList = [];
        if (! empty($validated['waves'])) {
            foreach ($validated['waves'] as $wave) {
                $wavesList[] = [
                    'id' => Str::slug($wave['id'], '_'),
                    'name' => strip_tags($wave['name']),
                    'start_date' => $wave['start_date'],
                    'end_date' => $wave['end_date'],
                ];
            }
        }

        PpdbSetting::setValue('waves', $wavesList);

        return redirect()->route('admin.ppdb.settings.edit')
            ->with('success', 'Gelombang PPDB|Jadwal gelombang berhasil diperbarui.');
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
            'requirements.*.is_active' => 'required|boolean',
        ]);

        $reqList = [];
        if (! empty($validated['requirements'])) {
            foreach ($validated['requirements'] as $req) {
                $reqList[] = [
                    'id' => Str::slug($req['id'], '_'),
                    'label' => strip_tags($req['label']),
                    'required' => (bool) $req['required'],
                    'is_active' => (bool) $req['is_active'],
                ];
            }
        }

        PpdbSetting::setValue('requirements', $reqList);

        return redirect()->route('admin.ppdb.settings.edit')
            ->with('success', 'Persyaratan Dokumen|Daftar berkas wajib berhasil disimpan.');
    }

    /**
     * Update dynamic custom form fields list in batch.
     */
    public function updateFields(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fields' => 'nullable|array',
            'fields.*.id' => 'required|string|alpha_dash',
            'fields.*.label' => 'required|string|max:100',
            'fields.*.type' => 'required|string|in:text,number,select,date,textarea',
            'fields.*.options' => 'nullable|string',
            'fields.*.required' => 'required|boolean',
            'fields.*.is_active' => 'required|boolean',
        ]);

        $fieldsList = [];
        $reserved = [
            'nama_lengkap', 'nisn', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'nomor_hp', 'email', 'nama_ayah', 'nama_ibu', 'alamat_lengkap', 'sekolah_asal',
            'ukuran_baju', 'foto_siswa', 'status', 'catatan_admin', 'submitted_at',
        ];

        $seenIds = [];

        if (! empty($validated['fields'])) {
            foreach ($validated['fields'] as $f) {
                $id = Str::slug($f['id'], '_');
                if (empty($id)) {
                    return redirect()->back()->withErrors(['fields' => 'Salah satu ID kolom kustom tidak valid.'])->withInput();
                }

                if (in_array($id, $reserved)) {
                    return redirect()->back()->withErrors(['fields' => 'Kolom "'.$f['label'].'" bertabrakan dengan kolom inti sistem.'])->withInput();
                }

                if (in_array($id, $seenIds)) {
                    return redirect()->back()->withErrors(['fields' => 'Kolom "'.$f['label'].'" terduplikasi dalam daftar.'])->withInput();
                }

                $seenIds[] = $id;

                // Process select options (comma separated string)
                $optionsArr = [];
                if ($f['type'] === 'select' && ! empty($f['options'])) {
                    $optionsArr = array_filter(array_map('trim', explode(',', $f['options'])));
                }

                $fieldsList[] = [
                    'id' => $id,
                    'label' => strip_tags($f['label']),
                    'type' => $f['type'],
                    'options' => $optionsArr,
                    'required' => (bool) $f['required'],
                    'is_active' => (bool) $f['is_active'],
                ];
            }
        }

        PpdbSetting::setValue('form_fields', $fieldsList);

        return redirect()->route('admin.ppdb.settings.edit')
            ->with('success', 'Pembangun Formulir|Daftar kolom kustom berhasil diperbarui.');
    }
}
