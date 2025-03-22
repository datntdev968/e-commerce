<?php

namespace App\Http\Resources\Permisstion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PermisstionResource extends JsonResource
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
            'created_at' => $createdAt->format('d-m-Y') . " (" . $createdAt->diffForHumans() . ")",
        ];
    }
}
