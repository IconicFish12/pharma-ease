<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'supplier_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            // FORMAT: unique:table,column,ignore_value,ignore_column_name
            'phone_number' => 'required|string|max:20|unique:suppliers',
            'address' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_name.required' => 'Nama supplier wajib diisi.',
            'contact_person.required' => 'Nama kontak person wajib diisi.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.unique' => 'Nomor telepon ini sudah digunakan supplier lain.',
            'address.required' => 'Alamat wajib diisi.',
        ];
    }
}
