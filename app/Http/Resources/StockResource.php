<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
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
            'sku' => $this->sku,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'unit_cost' => $this->unit_cost,
            'unit_price' => $this->unit_price,
            'tax' => $this->tax,
            'tax_type' => $this->tax_type,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
        ];
    }
}
