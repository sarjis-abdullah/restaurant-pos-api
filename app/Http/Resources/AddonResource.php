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
            'has_variations' => $this->has_variations,
            'branch_id' => $this->branch_id,
            'variations' => $this->when($this->needToInclude($request, 'addon.variations'), fn()=> new AddonVariationResourceCollection($this->variations)),
        ];
    }
}
