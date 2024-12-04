<?php

namespace App\Http\Requests\Variant;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "menu_item_id" => "sometimes"
        ];
    }
}
