<?php

namespace App\Http\Requests\Floor;

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
            'name' => 'required|string|unique:floors,name',
            'branch_id' => 'required|exists:branches,id',
            'company_id' => 'required|exists:branches,company_id',
        ];
    }
}
