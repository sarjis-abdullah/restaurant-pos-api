<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseProductResource extends Resource
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
            'purchase_id' => $this->purchase_id,
            'product_id' => $this->product_id,
            'stock_id' => $this->stock_id,
            'quantity' => $this->quantity,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'tax' => $this->tax,
            'tax_type' => $this->tax_type,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'allocated_shipping_cost' => $this->allocated_shipping_cost,
            'subtotal' => $this->subtotal,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'purchase' => $this->when($this->needToInclude($request, 'pp.purchase'), fn() => new PurchaseResource($this->purchase)),
            'stock' => $this->when($this->needToInclude($request, 'pp.stock'), fn() => new StockReturnResource($this->stock)),
            'product' => $this->when($this->needToInclude($request, 'pp.product'), fn() => new ProductResource($this->product)),
        ];
    }
}
