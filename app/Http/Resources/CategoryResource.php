<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends Resource
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
            'branch_id' => $this->branch_id,
            'menu_items' => $this->when($this->needToInclude($request, 'menu_items'), fn() => MenuItemResource::collection($this->menu_items()->paginate(2))),
//            'menu_items' => $this->menu_items,
        ];
    }
}
