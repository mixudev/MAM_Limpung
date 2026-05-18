<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'nama_lengkap'   => ['required', 'string', 'max:255'],
            'nisn'           => ['required', 'string', 'max:20', 'unique:ppdb_siswas,nisn'],
            'nomor_hp'       => ['required', 'string', 'max:15'],
            'email'          => ['required', 'email', 'max:255', 'unique:ppdb_siswas,email'],
            'foto_siswa'     => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'jenis_kelamin'  => ['required', 'in:L,P'],
            'tanggal_lahir'  => ['required', 'date', 'before:today'],
            'tempat_lahir'   => ['required', 'string', 'max:255'],
            'nama_ayah'      => ['required', 'string', 'max:255'],
            'nama_ibu'       => ['required', 'string', 'max:255'],
            'alamat_lengkap' => ['required', 'string', 'max:1000'],
            'sekolah_asal'   => ['required', 'string', 'max:255'],
            'ukuran_baju'    => ['required', 'in:S,M,L,XL,XXL,XXXL'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi.',
            'nisn.required'          => 'NISN wajib diisi.',
            'nisn.unique'            => 'NISN sudah terdaftar.',
            'nisn.max'               => 'NISN maksimal 20 karakter.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah terdaftar.',
            'foto_siswa.required'    => 'Foto siswa wajib diunggah.',
            'foto_siswa.image'       => 'File harus berupa gambar.',
            'foto_siswa.mimes'       => 'Format gambar harus jpeg, jpg, atau png.',
            'foto_siswa.max'         => 'Ukuran foto maksimal 2MB.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'       => 'Jenis kelamin tidak valid.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date'     => 'Format tanggal tidak valid.',
            'tanggal_lahir.before'   => 'Tanggal lahir harus sebelum hari ini.',
            'ukuran_baju.required'   => 'Ukuran baju wajib dipilih.',
            'ukuran_baju.in'         => 'Ukuran baju tidak valid.',
        ];
    }
}
