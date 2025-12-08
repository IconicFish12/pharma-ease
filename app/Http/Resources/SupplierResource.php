<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->supplier_id,
            'supplierName' => $this->supplier_name,
            'contactPerson' => $this->contact_person,
            'phone' => $this->phone,
            'address' => $this->address,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
