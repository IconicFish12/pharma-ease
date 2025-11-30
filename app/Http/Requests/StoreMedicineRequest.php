<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  true;
    }

    public function rules(): array
    {
        return [
            'medicine_name' => 'required|string|min:5|max:255',
            'sku' => 'required|string|max:100|unique:medicines,sku',
            'description' => 'nullable|string|min:10',
            'category_id' => 'required|exists:medicine_categories,category_id',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'stock' => 'required|integer|min:15',
            'price' => 'required|numeric|min:0',
            'expired_date' => 'required|date|after:today',
        ];
    }

    public function messages(): array
    {
        return [
            'medicine_name.required' => 'Nama obat wajib diisi.',
            'medicine_name.min' => 'Nama obat minimal harus 5 karakter.',
            'sku.required' => 'Kode SKU wajib diisi.',
            'sku.unique' => 'Kode SKU ini sudah terdaftar, gunakan kode lain.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
            'category_id.required' => 'Kategori obat wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak valid.',
            'stock.required' => 'Stok awal wajib diisi.',
            'stock.min' => 'Stok minimal harus 15.',
            'price.required' => 'Harga obat wajib diisi.',
            'expired_date.required' => 'Tanggal kedaluwarsa wajib diisi.',
            'expired_date.after' => 'Tanggal kedaluwarsa harus lebih dari hari ini.',
        ];
    }
}
