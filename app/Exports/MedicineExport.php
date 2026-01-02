<?php

namespace App\Exports;

use App\Models\Medicine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MedicineExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Medicine::with(['category', 'supplier'])->get();
    }

    public function headings(): array
    {
        return ['Name', 'SKU', 'Category', 'Supplier', 'Stock', 'Unit', 'Price', 'Expiry Date'];
    }

    public function map($medicine): array
    {
        return [
            $medicine->medicine_name,
            $medicine->sku,
            $medicine->category->name ?? '-',
            $medicine->supplier->supplier_name ?? '-',
            $medicine->stock,
            $medicine->unit,
            $medicine->price,
            $medicine->expired_date,
        ];
    }
}
