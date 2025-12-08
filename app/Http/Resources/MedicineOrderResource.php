<?php

namespace App\Http\Resources;

use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineOrderResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_id,
            'order_code' => $this->order_code,
            'transaction_date' => $this->order_date,
            'status_label' => $this->status,
            'total_amount_formatted' => 'Rp ' . number_format($this->total_price, 0, ',', '.'),
            'cashier' => new UserResource($this->whenLoaded('user')),
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'items' => $this->whenLoaded('medicines', function() {
                return $this->medicines->map(function($medicine) {
                    return [
                        'medicine_id' => $medicine->medicine_id,
                        'medicine_name' => $medicine->medicine_name,
                        'sku' => $medicine->sku,
                        'quantity_ordered' => $medicine->pivot->quantity,
                        'price_per_unit' => $medicine->pivot->unit_price,
                        'subtotal' => $medicine->pivot->subtotal,
                    ];
                });
            }),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
