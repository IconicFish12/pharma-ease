<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $id = $this->input('id');

        return [
            'name' => 'required|string|max:255',
            'emp_id' => 'required|string|unique:users,emp_id,' . $id . ',user_id',
            'email' => 'required|email|unique:users,email,' . $id . ',user_id',
            'password' => 'nullable|string|min:6',
            'role' => ['required', Rule::in(['admin', 'owner', 'pharmacist', 'cashier'])],
            'shift' => ['nullable', Rule::in(['pagi', 'siang', 'malam'])],
            'date_of_birth' => 'required|date',
            'alamat' => 'required|string',
            'salary' => 'nullable|integer|min:0',
            'start_date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'emp_id.required' => 'ID Karyawan (NIP) wajib diisi.',
            'emp_id.unique' => 'ID Karyawan ini sudah digunakan oleh pengguna lain.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'role.required' => 'Peran (Role) wajib dipilih.',
            'role.in' => 'Peran yang dipilih tidak valid.',
            'shift.in' => 'Pilihan shift tidak valid.',
            'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'salary.integer' => 'Gaji harus berupa angka.',
            'start_date.required' => 'Tanggal mulai bekerja wajib diisi.',
        ];
    }
}
