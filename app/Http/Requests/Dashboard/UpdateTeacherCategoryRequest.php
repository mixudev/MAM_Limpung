<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit-teacher-categories');
    }

    public function rules(): array
    {
        $id = $this->route('teacher_category')?->id ?? $this->route('teacher_category');

        return [
            'name' => ['required', 'string', 'max:100', 'unique:teacher_categories,name,'.$id],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
        ];
    }
}
