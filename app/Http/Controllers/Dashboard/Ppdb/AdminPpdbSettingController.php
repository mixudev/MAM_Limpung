<?php

namespace App\Http\Controllers\Dashboard\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\PpdbSetting;
use App\Models\RegistrationWave;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPpdbSettingController extends Controller
{
    public function edit(): View
    {
        $academicYears = AcademicYear::with('waves')->orderBy('year', 'desc')->get();
        $general = PpdbSetting::getValue('general', ['is_open' => true]);
        $requirements = PpdbSetting::getValue('requirements', []);
        $formFields = PpdbSetting::getValue('form_fields', []);

        return view('dashboard.admin.ppdb.settings', compact('academicYears', 'general', 'requirements', 'formFields'));
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'is_open' => 'required|boolean',
        ]);

        PpdbSetting::setValue('general', [
            'is_open' => (bool) $validated['is_open'],
        ]);

        Cache::forget('ppdb_is_open');

        return redirect()->route('admin.ppdb.settings.edit')
            ->with('success', 'Konfigurasi PPDB|Pengaturan umum berhasil diperbarui.');
    }

    // -----------------------------------------------------------------------
    //  Academic Years CRUD
    // -----------------------------------------------------------------------

    public function showYear(int $id): View
    {
        $academicYear = AcademicYear::findOrFail($id);
        $items = RegistrationWave::where('academic_year_id', $id)
            ->orderBy('start_date')
            ->orderBy('type')
            ->get();
        $waves = $items->where('type', 'wave');
        $standaloneDates = $items->where('type', 'date');

        $wavesData = $items->where('type', 'wave')->map(fn ($w) => [
            'id' => $w->id,
            'name' => $w->name,
            'start_date' => $w->start_date?->format('Y-m-d') ?? '',
            'end_date' => $w->end_date?->format('Y-m-d') ?? '',
            'is_active' => $w->is_active,
        ]);

        return view('dashboard.admin.ppdb.year-detail', compact('academicYear', 'wavesData', 'waves', 'standaloneDates'));
    }

    public function storeYear(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:2100|unique:academic_years,year',
        ]);

        $year = (int) $validated['year'];

        AcademicYear::create([
            'year' => $year,
            'name' => $year.'/'.($year + 1),
            'is_active' => ! AcademicYear::where('is_active', true)->exists(),
        ]);

        Cache::forget('ppdb_available_years');

        return redirect()->route('admin.ppdb.settings.edit')
            ->with('success', 'Tahun Ajaran|Tahun pelajaran '.$year.' berhasil ditambahkan.');
    }

    public function activateYear(int $id): RedirectResponse
    {
        $academicYear = AcademicYear::findOrFail($id);

        AcademicYear::where('is_active', true)->update(['is_active' => false]);
        $academicYear->update(['is_active' => true]);

        Cache::forget('ppdb_available_years');
        Cache::forget('ppdb_is_open');

        return redirect()->back()
            ->with('success', 'Tahun Ajaran|Tahun pelajaran '.$academicYear->name.' diaktifkan.');
    }

    public function destroyYear(int $id): RedirectResponse
    {
        $academicYear = AcademicYear::findOrFail($id);

        if ($academicYear->waves()->exists()) {
            return redirect()->route('admin.ppdb.settings.edit')
                ->withErrors(['error' => 'Tahun ajaran "'.$academicYear->name.'" masih memiliki gelombang pendaftaran. Hapus semua gelombang terlebih dahulu.']);
        }

        $academicYear->delete();

        Cache::forget('ppdb_available_years');
        Cache::forget('ppdb_is_open');

        return redirect()->route('admin.ppdb.settings.edit')
            ->with('success', 'Tahun Ajaran|Tahun pelajaran '.$academicYear->name.' berhasil dihapus.');
    }

    // -----------------------------------------------------------------------
    //  Waves CRUD
    // -----------------------------------------------------------------------

    public function storeWave(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        RegistrationWave::create([
            'academic_year_id' => (int) $validated['academic_year_id'],
            'slug' => Str::slug($validated['name']),
            'name' => strip_tags($validated['name']),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'is_active' => true,
        ]);

        Cache::forget('ppdb_is_open');

        return redirect()->back()
            ->with('success', 'Gelombang PPDB|Gelombang baru berhasil ditambahkan.');
    }

    public function toggleWave(int $id): RedirectResponse
    {
        $wave = RegistrationWave::findOrFail($id);
        $wave->update(['is_active' => ! $wave->is_active]);

        Cache::forget('ppdb_is_open');

        return redirect()->back()
            ->with('success', 'Gelombang PPDB|Status gelombang "'.$wave->name.'" diperbarui.');
    }

    public function updateWave(Request $request, int $id): RedirectResponse
    {
        $wave = RegistrationWave::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $wave->update([
            'slug' => Str::slug($validated['name']),
            'name' => strip_tags($validated['name']),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'is_active' => $validated['is_active'] ?? $wave->is_active,
        ]);

        Cache::forget('ppdb_is_open');

        return redirect()->back()
            ->with('success', 'Gelombang PPDB|Gelombang berhasil diperbarui.');
    }

    public function destroyWave(int $id): RedirectResponse
    {
        $wave = RegistrationWave::findOrFail($id);

        $wave->ppdbSiswas()->update(['registration_wave_id' => null]);
        $wave->delete();

        Cache::forget('ppdb_is_open');

        return redirect()->back()
            ->with('success', 'Gelombang PPDB|Gelombang berhasil dihapus.');
    }

    // -----------------------------------------------------------------------
    //  Wave Dates CRUD
    // -----------------------------------------------------------------------

    public function storeWaveDate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:100',
            'date' => 'required|date',
        ]);

        RegistrationWave::create([
            'type' => 'date',
            'academic_year_id' => (int) $validated['academic_year_id'],
            'slug' => Str::slug($validated['name']),
            'name' => strip_tags($validated['name']),
            'start_date' => $validated['date'],
            'end_date' => null,
            'is_active' => true,
        ]);

        return redirect()->back()
            ->with('success', 'Tanggal Penting|Tanggal "'.strip_tags($validated['name']).'" berhasil ditambahkan.');
    }

    public function destroyWaveDate(int $id): RedirectResponse
    {
        $date = RegistrationWave::where('type', 'date')->findOrFail($id);
        $name = $date->name;
        $date->delete();

        return redirect()->back()
            ->with('success', 'Tanggal Penting|Tanggal "'.$name.'" berhasil dihapus.');
    }

    // -----------------------------------------------------------------------
    //  Requirements & Fields (unchanged from legacy)
    // -----------------------------------------------------------------------

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
