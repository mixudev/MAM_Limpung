<?php

namespace App\Http\Requests\Dashboard\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'school_name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'about_short' => ['nullable', 'string', 'max:1000'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'google_maps_iframe' => ['nullable', 'string', 'max:5000'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            // Kepala Sekolah
            'headmaster_name' => ['nullable', 'string', 'max:255'],
            'headmaster_nip' => ['nullable', 'string', 'max:50'],
            'headmaster_phone' => ['nullable', 'string', 'max:50'],
            'headmaster_signature' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
            // Data Sekolah
            'school_motto' => ['nullable', 'string', 'max:500'],
            'school_code' => ['nullable', 'string', 'max:50'],
            'school_founding_year' => ['nullable', 'integer', 'min:1900', 'max:'.date('Y')],
            'school_status' => ['nullable', 'in:Negeri,Swasta'],
            'school_accreditation' => ['nullable', 'string', 'max:10'],
            'school_website' => ['nullable', 'url', 'max:255'],
            'school_email_official' => ['nullable', 'email', 'max:255'],
        ];
    }
}
