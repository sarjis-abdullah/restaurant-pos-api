<?php

namespace App\Http\Requests\Table;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'booking_from' => 'required|date|date_format:Y-m-d H:i:s',
            'booking_to' => 'required|date|date_format:Y-m-d H:i:s',
        ];
    }
}
