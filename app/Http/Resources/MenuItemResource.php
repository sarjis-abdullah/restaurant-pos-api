<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'price'             => $this->price,
            'quantity'          => $this->quantity,
            'category_id'       => $this->category_id,
            'menu_id'           => $this->menu_id,
            'discount_id'       => $this->discount_id,
            'tax_id'            => $this->tax_id,
            'tax_included'      => $this->tax_included,
            'type'              => $this->type,
            'description'       => $this->description,
            'ingredients'       => $this->ingredients,
            'preparation_time'  => $this->preparation_time,
            'serves'            => $this->serves,
            'allow_other_discount' => $this->allow_other_discount,
            'menu' => $this->when($this->needToInclude($request, 'menu'), fn() => new MenuResource($this->menu)),
            'discount' => $this->when($this->needToInclude($request, 'discount'), fn() => new DiscountResource($this->discount)),
            'tax' => $this->when($this->needToInclude($request, 'tax'), fn() => new TaxResource($this->tax)),
            'category' => $this->when($this->needToInclude($request, 'category'), fn() => new CategoryResource($this->category)),
            'variants' => $this->when($this->needToInclude($request, 'variants'), fn() => VariantResource::collection($this->variants()->paginate(2))),
            'addons' => $this->when($this->needToInclude($request, 'addons'), fn() => AddonResource::collection($this->addons()->paginate(2))),
        ];
    }
}
