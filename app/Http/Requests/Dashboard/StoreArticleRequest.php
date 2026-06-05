<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create-articles');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255', 'unique:articles,judul'],
            'category_id' => ['required', 'exists:article_categories,id'],
            'ringkasan' => ['nullable', 'string', 'max:500'],
            'konten' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // Strict upload check
            'temp_thumbnail' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,pending,published,archived'],
            'published_at' => ['nullable', 'required_if:status,published', 'date'],
            'seo_meta_title' => ['nullable', 'string', 'max:255'],
            'seo_meta_description' => ['nullable', 'string', 'max:500'],
            'seo_meta_keywords' => ['nullable', 'string', 'max:255'],
            'seo_focus_keyword' => ['nullable', 'string', 'max:100'],
            'seo_canonical_url' => ['nullable', 'url', 'max:500'],
            'seo_is_indexed' => ['nullable', 'boolean'],
            'seo_is_followed' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'judul' => 'Judul Artikel',
            'category_id' => 'Kategori Artikel',
            'ringkasan' => 'Ringkasan Artikel',
            'konten' => 'Konten Artikel',
            'thumbnail' => 'Gambar Mini (Thumbnail)',
            'temp_thumbnail' => 'Berkas Gambar Sementara',
            'status' => 'Status Publikasi',
            'published_at' => 'Waktu Publikasi',
        ];
    }
}
