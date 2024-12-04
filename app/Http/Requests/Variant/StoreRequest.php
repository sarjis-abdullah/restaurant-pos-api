<?php

namespace App\Http\Requests\Variant;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'menu_item_id' => 'required|integer|exists:menu_items,id',
            'variants' => 'required||required|array',
            'variants.*.type' => 'required|string',
            'variants.*.name' => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
        ];
    }
}
