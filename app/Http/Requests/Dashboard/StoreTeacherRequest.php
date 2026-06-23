<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Teacher;
use App\Models\TeacherCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-teachers');
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'nip' => ['nullable', 'string', 'max:30', 'unique:teachers,nip'],
            'nama' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:50'],
            'jurusan' => ['nullable', 'string', 'max:100'],
            'tanggal_masuk' => ['nullable', 'date'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'quote' => ['nullable', 'string', 'max:500'],
            'teacher_category_id' => ['nullable', 'exists:teacher_categories,id'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (! $this->filled('teacher_category_id')) {
                    return;
                }

                $category = TeacherCategory::find($this->teacher_category_id);

                if (! in_array($category?->slug, ['kepala-sekolah', 'wakil-kepala-sekolah'], true)) {
                    return;
                }

                $label = $category->slug === 'kepala-sekolah'
                    ? 'Kepala Sekolah'
                    : 'Wakil Kepala Sekolah';

                $exists = Teacher::where('teacher_category_id', $category->id)->exists();

                if ($exists) {
                    $validator->errors()->add(
                        'teacher_category_id',
                        "Kategori {$label} sudah terisi oleh guru lain. Hanya satu guru yang boleh memiliki kategori ini.",
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama guru wajib diisi.',
            'email.required' => 'Email login wajib diisi.',
            'email.unique' => 'Email login sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'nip.unique' => 'NIP sudah terdaftar.',
        ];
    }
}
