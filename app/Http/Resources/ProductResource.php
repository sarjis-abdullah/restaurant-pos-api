<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'unit' => $this->unit,
            'barcode' => $this->barcode,
            'status' => $this->status,
            'stocks' => $this->when($this->needToInclude($request, 'stocks'), fn()=> new StockResourceCollection($this->stocks)),
        ];
    }
}
