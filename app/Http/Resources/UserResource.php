<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->user_id,
            'name' => $this->name,
            'emp_id' => $this->emp_id,
            'email' => $this->email,
            'role' => $this->role,
            'shift' => $this->shift,
            'date_of_birth' => Carbon::parse($this->date_of_birth)->format('d M Y'),
            'address' => $this->alamat,
            'salary' =>'Rp ' . number_format($this->salary, 0, ',', '.'),
            'start_date' => Carbon::parse($this->start_date)->format('d M Y'),
            'avatar' => $this->profile_avatar,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
