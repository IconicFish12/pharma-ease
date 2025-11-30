<?php

namespace App\Http\Resources;

use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MedicineOrderResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->order_id,
            'orderCode' => $this->order_code,
            'orderDate' => date('d M Y', strtotime($this->order_date)),
            'totalPrice' => 'Rp ' . number_format($this->total_price, 0, ',', '.'),
            'status' => $this->status,
            'user' => UserResource::collection($this->whenLoaded('user')),
            'supplier' => SupplierResource::collection($this->whenLoaded('supplier')),
            'createdAt' => Carbon::parse($this->created_at)->diffForHumans(),
            'updatedAt' => Carbon::parse($this->updated_at)->diffForHumans()
        ];
    }
}
