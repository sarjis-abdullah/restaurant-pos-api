<?php

namespace App\Http\Requests\Product;

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
            'name' => ['required', 'string', 'min:1'],
            'description' => ['string', 'min:1'],
            'unit' => ['required', 'string', 'min:1'],
            'barcode' => ['string', 'min:1'],
            'status' => ['required', 'string', 'min:1']
        ];
    }
}
