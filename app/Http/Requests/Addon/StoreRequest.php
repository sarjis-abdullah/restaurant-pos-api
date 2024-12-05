<?php

namespace App\Http\Requests\Addon;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'menu_item_id' => 'required|integer|exists:menu_items,id',
            'addons' => 'required||required|array',
            'addons.*.description' => 'sometimes|string',
            'addons.*.name' => 'required|string',
            'addons.*.price' => 'required|numeric|min:0',
        ];
    }
}
