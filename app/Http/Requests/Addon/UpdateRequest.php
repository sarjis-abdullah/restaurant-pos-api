<?php

namespace App\Http\Requests\Addon;

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
            'name' => 'sometimes|string',
            'base_price' => 'sometimes|numeric',
            'has_variations' => 'sometimes|boolean',
//            'branch_id' => 'sometimes|exists:branches,id',
        ];
    }
}
