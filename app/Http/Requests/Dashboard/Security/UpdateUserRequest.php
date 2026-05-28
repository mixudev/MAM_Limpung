<?php

namespace App\Http\Requests\Dashboard\Security;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit-users');
    }

    public function rules(): array
    {
        $userId = $this->route('user') instanceof User
            ? $this->route('user')->id
            : $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$userId],
            'password' => ['nullable', 'string', 'min:8'],
            'is_active' => ['nullable', 'boolean'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Alamat email harus valid.',
            'email.unique' => 'Alamat email sudah terdaftar.',
            'password.min' => 'Password minimal harus terdiri dari 8 karakter.',
            'roles.required' => 'Silakan pilih minimal satu role untuk user ini.',
            'roles.*.exists' => 'Role yang dipilih tidak valid.',
        ];
    }
}
