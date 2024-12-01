<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class StoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payments' => 'required|array', // Ensure orders is an array
            'orders.*.order_id' => 'required|integer|exists:orders,id', // Validate menu_item_id
            'orders.*.method' => 'required|string', // Validate menu_item_id
            'orders.*.amount' => 'required|integer', // Validate menu_item_id
            'orders.*.reference_number' => 'sometimes', // Validate quantity
            'orders.*.transaction_number' => 'sometimes', // Validate quantity
        ];
    }
    protected function getOrderIndex($attribute): ?string
    {
        // Extract the order index from the attribute name (e.g. orders.0.variant_id -> 0)
        preg_match('/orders\.(\d+)\.variant_id/', $attribute, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'orders.required' => 'The orders field is required.',
            'orders.*.menu_item_id.required' => 'The menu item ID is required.',
            'orders.*.menu_item_id.exists' => 'The selected menu item does not exist.',
            'orders.*.quantity.required' => 'The quantity is required.',
            'orders.*.quantity.min' => 'The quantity must be at least 1.',
            'orders.*.addons.array' => 'The addons must be an array.',
            'orders.*.addons.*.addon_id.exists' => 'The selected addon does not exist.',
            'orders.*.addons.*.quantity.min' => 'The addon quantity must be at least 1.',
            'orders.*.addons.*.variant_id.exists' => 'The selected addon variant does not exist.',
            'orders.*.variant_id.exists' => 'The selected menu item variant does not exist.',
        ];
    }
}
