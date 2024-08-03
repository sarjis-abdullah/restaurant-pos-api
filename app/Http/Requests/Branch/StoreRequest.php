<?php

namespace App\Http\Requests\Branch;

use App\Enums\BranchType;
use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => 'required|string',
            'company_id' => 'required|exists:companies,id',
            'type' => ['required', 'string', Rule::enum(BranchType::class)],

        ];
    }
}
