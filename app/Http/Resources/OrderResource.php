<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends Resource
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
            'discount' => $this->discount,
            'tax' => $this->tax,
            'total_amount' => $this->total_amount,
            'addons_total' => $this->addons_total,
            'status' => $this->status,
            'table_id' => $this->table_id,
            'type' => $this->type,
            'pickup_date' => $this->pickup_date,
            'branch_id' => $this->branch_id,
            'prepare_by' => $this->prepare_by ?? null,
            'created_by' => $this->created_by,
            'order_by'  => $this->order_by ?? null,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'order_items' => $this->when($this->needToInclude($request, 'order_items'), fn()=> new OrderResourceCollection($this->order_items)),
        ];
    }
}
