<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddonResource extends Resource
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
            'price' => $this->price,
            'has_variants' => $this->has_variants,
            'branch_id' => $this->branch_id,
            'variants' => $this->when($this->needToInclude($request, 'addon.variants'), fn()=> new AddonVariationResourceCollection($this->variants)),
        ];
    }
}
