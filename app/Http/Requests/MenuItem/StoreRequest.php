<?php

namespace App\Http\Requests\MenuItem;

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
            'name' => 'required|string',
            'price' => 'required|string',
            'category_id' =>'required|string',
            'discount_id' => 'required|string',
            'tax_id' => 'required|string',
            'menu_id' =>'required|string',
            'branch_id' =>'required|string',
            'quantity' =>'sometimes',
//            'type' => 'required|string',
        ];
    }
}
