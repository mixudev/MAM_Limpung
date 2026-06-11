<?php

namespace App\Http\Requests\Dashboard\Galeri;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGaleriRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $galeri = $this->route('galeri');

        return $galeri && $this->user()->can('update', $galeri);
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
            'kategori' => ['required', 'string', 'in:Belajar,Ekskul,Fasilitas,Event Seru'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'links' => ['nullable', 'array'],
            'links.*' => [
                'nullable',
                'url',
                // Whitelist domain yang diizinkan untuk link foto eksternal
                // Mencegah SSRF, internal network access, dan konten berbahaya
                'regex:/^https:\/\/(drive\.google\.com|photos\.google\.com|lh[0-9]+\.googleusercontent\.com|res\.cloudinary\.com|i\.imgur\.com|imgur\.com|ibb\.co|i\.ibb\.co|postimg\.cc|i\.postimg\.cc|dropbox\.com|dl\.dropboxusercontent\.com|onedrive\.live\.com|1drv\.ms)\//i',
            ],
            'existing_photos' => ['nullable', 'array'],
            'existing_photos.*' => ['required', 'integer'],
            'cover_type' => ['required', 'string', 'in:existing,file,link'],
            'cover_index' => ['required', 'integer'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hasPhotos = $this->hasFile('photos') && count($this->file('photos')) > 0;
            $hasLinks = $this->filled('links') && count(array_filter($this->input('links'))) > 0;
            $hasExisting = $this->filled('existing_photos') && count($this->input('existing_photos')) > 0;

            if (! $hasPhotos && ! $hasLinks && ! $hasExisting) {
                $validator->errors()->add('photos', 'Harus menyisakan minimal satu foto yang ada, mengunggah foto baru, atau menyertakan tautan foto.');
            }
        });
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'judul' => 'Judul Galeri',
            'deskripsi' => 'Deskripsi',
            'kategori' => 'Kategori',
            'tahun' => 'Tahun',
            'photos' => 'Foto Unggahan Baru',
            'photos.*' => 'Berkas Foto',
            'links' => 'Tautan Foto Baru',
            'links.*' => 'Tautan URL Foto',
            'cover_type' => 'Tipe Sampul',
            'cover_index' => 'Indeks Sampul',
        ];
    }
}
