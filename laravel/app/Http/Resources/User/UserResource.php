<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'key' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?? 'Unknown',
            'address' => $this->address ?? 'Unknown',
            'gender' => $this->gender ? ($this->gender == 'male' ? 'Nam' : 'Nữ') : 'Unknown',
            'uni_code' => $this->uni_code,
            'birthday' => $this->birthday ? ($this->birthday->format('d/m/Y') . ' (' . date_diff(date_create($this->birthday), date_create('today'))->y . ' tuổi)') : 'Unknown',
            'created_at' => $this->created_at->format('d/m/Y'),
            'published' => $this->published,
        ];
    }
}
