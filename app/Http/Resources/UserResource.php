<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResource extends ResourceCollection
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
            'createdAt' => Carbon::parse($this->created_at)->diffForHumans(),
            'updatedAt' => Carbon::parse($this->updated_at)->diffForHumans()
        ];
    }
}
