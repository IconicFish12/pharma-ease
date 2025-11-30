<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineCategoryResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->category_id,
            'categoryName' => $this->category_name,
            'createdAt' => Carbon::parse($this->created_at)->diffForHumans(),
            'updatedAt' => Carbon::parse($this->updated_at)->diffForHumans()
        ];
    }
}
