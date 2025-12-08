<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicineOrderRequest extends FormRequest
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
            'user_id' => 'required|exists:users,user_id',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'order_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,medicine_id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User wajib dipilih.',
            'supplier_id.required' => 'Supplier harus dipilih.',
            'medicines.required' => 'Minimal harus ada satu obat yang dipilih.',
            'medicines.*.medicine_id.required' => 'Obat tidak valid.',
            'medicines.*.quantity.min' => 'Jumlah obat minimal 1.',
        ];
    }
}
