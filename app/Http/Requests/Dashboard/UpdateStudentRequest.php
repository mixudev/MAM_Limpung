<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit-students');
    }

    public function rules(): array
    {
        $studentId = $this->route('student')?->id ?? $this->route('student');

        return [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$this->route('student')?->user_id],
            'password' => ['nullable', 'string', 'min:8'],
            'nis' => ['nullable', 'string', 'max:30', 'unique:students,nis,'.$studentId],
            'nisn' => ['nullable', 'string', 'max:30', 'unique:students,nisn,'.$studentId],
            'nama' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'nama_ayah' => ['nullable', 'string', 'max:100'],
            'nama_ibu' => ['nullable', 'string', 'max:100'],
            'pekerjaan_ayah' => ['nullable', 'string', 'max:100'],
            'pekerjaan_ibu' => ['nullable', 'string', 'max:100'],
            'alamat_orang_tua' => ['nullable', 'string'],
            'no_telepon_orang_tua' => ['nullable', 'string', 'max:20'],
            'tanggal_masuk' => ['nullable', 'date'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama siswa wajib diisi.',
            'email.required' => 'Email login wajib diisi.',
            'email.unique' => 'Email login sudah terdaftar.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'nisn.unique' => 'NISN sudah terdaftar.',
        ];
    }
}
