<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $createdAt = Carbon::parse($this->created_at);
        return [
            'key' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'permissions' => implode(', ', $this->permissions->pluck('name')->map(function ($permission) {
                return str_replace(' catalogues', '', $permission);
            })->toArray()),

            'created_at' => $createdAt->format('d-m-Y') . " (" . $createdAt->diffForHumans() . ")",
        ];
    }
}
