<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'amount' => $this->round_off_amount,
            'round_off_amount' => $this->round_off_amount,
            'status' => $this->status,
            'method' => $this->method,
            'reference_number' => $this->reference_number,
            'transaction_number' => $this->transaction_number,
            'transaction_id' => $this->transaction_id,
            'order_id' => $this->order_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
