<?php

namespace App\Http\Resources\Permisstion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PermisstionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (
            $this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator ||
            $this->resource instanceof \Illuminate\Pagination\Paginator
        ) {
            return [
                'data' => $this->collection->map(function ($brand) {
                    return new PermisstionResource($brand);
                }),
                'total'        => $this->total(),
                'per_page'     => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page'    => $this->lastPage(),
            ];
        }

        return parent::toArray($request);
    }
}
