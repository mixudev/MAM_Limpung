<?php

namespace App\Http\Requests\Dashboard\Announcement;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnounceAlertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:65000'],
            'images' => ['nullable', 'array'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'retained_images' => ['nullable', 'array'],
            'retained_images.*' => ['required', 'string'],
            'main_image_path' => ['nullable', 'string'],
            'main_image_name' => ['nullable', 'string'],
            'action_url' => ['nullable', 'url', 'max:500'],
            'action_text' => ['nullable', 'string', 'max:100'],
            'popup_size' => ['required', 'string', 'in:sm,md,lg,xl'],
            'display_frequency' => ['required', 'string', 'in:once_per_session,every_load'],
            'target_page' => ['required', 'string', 'in:all_pages,frontend.home,frontend.ppdb.index,frontend.article.index,frontend.jurusan,frontend.kurikulum,frontend.ekstrakurikuler,frontend.prestasi,frontend.galeri,frontend.profile,frontend.contact'],
            'is_active' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);
    }
}
