<?php

namespace App\Http\Requests\Dashboard\Prestasi;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePrestasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create-achievements');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'temp_foto' => ['nullable', 'string'],
            'tingkat' => ['required', 'string', 'in:sekolah,kabupaten,provinsi,nasional,internasional'],
            'jenis' => ['required', 'string', 'in:akademik,non_akademik'],
            'penyelenggara' => ['nullable', 'string', 'max:255'],
            'peraih' => ['required', 'string', 'max:255'],
            'juara' => ['nullable', 'string', 'max:50'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'tanggal_prestasi' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'judul' => 'Judul Prestasi',
            'deskripsi' => 'Deskripsi',
            'foto' => 'Foto Prestasi',
            'tingkat' => 'Tingkat Prestasi',
            'jenis' => 'Jenis Prestasi',
            'penyelenggara' => 'Penyelenggara',
            'peraih' => 'Peraih Prestasi',
            'juara' => 'Juara',
            'tahun' => 'Tahun',
            'tanggal_prestasi' => 'Tanggal Prestasi',
            'is_featured' => 'Tampilkan Utama',
        ];
    }
}
