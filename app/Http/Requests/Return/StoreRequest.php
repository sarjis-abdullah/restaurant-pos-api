<?php

namespace App\Http\Requests\Return;

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
            'stock_id' => ['required', 'exists:stocks,id'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'reason' => ['nullable'],
        ];
    }
}
