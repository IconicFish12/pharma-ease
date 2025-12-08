<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->medicine_id,
            'medicineName' => $this->medicine_name,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'expiredDate' => date('d M Y', strtotime($this->expired_date)) ?? null,
            'price' => 'Rp ' . number_format($this->price, 0, ',', '.'),
            'category' => new MedicineCategoryResource($this->whenLoaded('category')),
            'suplier' => new SupplierResource($this->whenLoaded('supplier')),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
