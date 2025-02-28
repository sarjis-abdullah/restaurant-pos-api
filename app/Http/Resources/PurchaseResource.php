<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends Resource
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
            'supplier_id' => $this->supplier_id,
            'purchase_date' => $this->purchase_date,
            'total_amount' => $this->total_amount,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'shipping_cost' => $this->shipping_cost,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'due' => $this->when($this->needToInclude($request, 'purchase.due'), fn() => $this->getDueAmountAttribute()),
            'supplier' => $this->when($this->needToInclude($request, 'purchase.supplier'), fn() => new SupplierResource($this->supplier)),
            'payments' => $this->when($this->needToInclude($request, 'purchase.payments'), fn() => new PaymentResourceCollection($this->payments)),
            'purchaseProducts' => $this->when($this->needToInclude($request, 'purchaseProducts'), fn() => new PurchaseProductResourceCollection($this->purchaseProducts)),
        ];
    }
}
