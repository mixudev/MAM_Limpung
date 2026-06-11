<?php

namespace App\Http\Requests\Frontend;

use App\Models\PpdbSetting;
use App\Support\PpdbTempUploadManager;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PpdbStoreRequest extends FormRequest
{
    /**
     * Siapapun yang bisa mengakses route ini boleh submit formulir ini.
     * Proteksi dilakukan di level route (throttle) dan validasi di sini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        $rules = [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:20', 'unique:ppdb_siswas,nisn'],
            'nomor_hp' => ['required', 'string', 'max:15'],
            'email' => ['required', 'email', 'max:255', 'unique:ppdb_siswas,email'],
            'foto_siswa' => PpdbTempUploadManager::fileRules('foto_siswa'),
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'nama_ayah' => ['required', 'string', 'max:255'],
            'nama_ibu' => ['required', 'string', 'max:255'],
            'alamat_lengkap' => ['required', 'string', 'max:1000'],
            'sekolah_asal' => ['required', 'string', 'max:255'],
            'ukuran_baju' => ['required', 'in:S,M,L,XL,XXL,XXXL'],
        ];

        $requirements = PpdbSetting::getValue('requirements', []);
        foreach ($requirements as $req) {
            if ($req['id'] === 'foto') {
                continue;
            }

            $rules[$req['id']] = PpdbTempUploadManager::fileRules(
                $req['id'],
                (bool) ($req['required'] ?? false)
            );
        }

        $formFields = PpdbSetting::getValue('form_fields', []);
        foreach ($formFields as $field) {
            // Sanitasi field ID dari database — hanya izinkan karakter aman
            // Mencegah injection ke validation rules via field ID berbahaya
            $fieldId = preg_replace('/[^a-zA-Z0-9_]/', '', $field['id'] ?? '');
            if (empty($fieldId)) {
                continue;
            }

            $fieldRules = [];
            $fieldRules[] = $field['required'] ? 'required' : 'nullable';

            if ($field['type'] === 'number') {
                $fieldRules[] = 'numeric';
            } elseif ($field['type'] === 'date') {
                $fieldRules[] = 'date';
            } else {
                $fieldRules[] = 'string';
                $fieldRules[] = 'max:500';
            }

            if ($field['type'] === 'select' && ! empty($field['options'])) {
                // Sanitasi options — hapus karakter yang bisa memanipulasi validation rules
                $cleanOptions = array_filter(
                    array_map(
                        fn ($o) => preg_replace('/[,|\\\\]/', '', (string) $o),
                        (array) $field['options']
                    )
                );
                if (! empty($cleanOptions)) {
                    $fieldRules[] = 'in:'.implode(',', $cleanOptions);
                }
            }

            $rules[$fieldId] = $fieldRules;
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator): void
    {
        PpdbTempUploadManager::persistFromRequest($this);

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN sudah terdaftar.',
            'nisn.max' => 'NISN maksimal 20 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'foto_siswa.required' => 'Foto siswa wajib diunggah.',
            'foto_siswa.image' => 'File harus berupa gambar.',
            'foto_siswa.mimes' => 'Format gambar harus jpeg, jpg, atau png.',
            'foto_siswa.max' => 'Ukuran foto maksimal 2MB.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal tidak valid.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'ukuran_baju.required' => 'Ukuran baju wajib dipilih.',
            'ukuran_baju.in' => 'Ukuran baju tidak valid.',
        ];
    }
}
