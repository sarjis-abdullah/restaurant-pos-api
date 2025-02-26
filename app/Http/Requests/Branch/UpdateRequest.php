<?php

namespace App\Http\Requests\Branch;

use App\Enums\BranchType;
use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string',
            'company_id' => 'sometimes|required|exists:companies,id',
            'type' => ['sometimes', 'required', 'string', Rule::enum(BranchType::class)],
        ];
    }
}
