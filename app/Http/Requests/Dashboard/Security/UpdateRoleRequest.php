<?php

namespace App\Http\Requests\Dashboard\Security;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super-admin');
    }

    public function rules(): array
    {
        $roleId = $this->route('role')?->id;

        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$roleId, 'regex:/^[a-zA-Z0-9\-]+$/'],
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'level' => ['required', 'integer', 'min:0', 'max:100'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Format identifier role harus berupa huruf, angka, dan tanda hubung (-) saja (kebab-case).',
            'name.unique' => 'Identifier role sudah digunakan.',
        ];
    }
}
