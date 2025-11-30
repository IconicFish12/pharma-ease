<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicineOrderRequest extends FormRequest
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
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'order_date' => 'required|date',
            'status' => 'required|in:Pending,Completed,Cancelled',
            // Validasi Array Medicines
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,medicine_id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Supplier harus dipilih.',
            'medicines.required' => 'Minimal harus ada satu obat yang dipilih.',
            'medicines.*.medicine_id.required' => 'Obat tidak valid.',
            'medicines.*.quantity.min' => 'Jumlah obat minimal 1.',
        ];
    }
}
