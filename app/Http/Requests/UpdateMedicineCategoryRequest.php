<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicineCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:medicine_categories|string',
            'description' => 'required|string'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'input nama kategori tidak boleh kosong',
            'description.required' => 'input deskripsi kategori tidak boleh kosong',
            'description.string' => 'input deskripsi kategori hanya boleh disi karakter huruf',
            'name.unique' => 'input nama kategori sudah terdaftar',
            'name.string' => 'input nama kategori kategori hanya boleh disi karakter huruf',

        ];
    }
}
