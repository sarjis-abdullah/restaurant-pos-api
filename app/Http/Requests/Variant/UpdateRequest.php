<?php

namespace App\Http\Requests\Variant;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends Request
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
            'price' => 'required|numeric',
            'type' => 'required|string',
        ];
    }
}
