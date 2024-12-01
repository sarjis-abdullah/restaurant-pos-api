<?php

namespace App\Http\Requests\Order;

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
            'orders' => 'required|array', // Ensure orders is an array
            'orders.*.menu_item_id' => 'required|integer|exists:menu_items,id', // Validate menu_item_id
            'orders.*.quantity' => 'required|integer|min:1', // Validate quantity
            'orders.*.addons' => 'array', // Addons is optional but if present, it must be an array
            'orders.*.addons.*.addon_id' => 'required|integer|exists:addons,id', // Validate addon_id, if addons exist
            'orders.*.addons.*.variant_id' => 'required|integer|exists:addon_variants,id', // Optional validation for variant_id
            'orders.*.variant_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    $menuItemId = $this->input('orders.' . $this->getOrderIndex($attribute) . '.menu_item_id');
                    if ($menuItemId && $value) {
                        $exists = \DB::table('variants')
                            ->where('menu_item_id', $menuItemId)
                            ->where('id', $value)
                            ->exists();

                        if (!$exists) {
                            $fail('The selected variant does not exist for this menu item.');
                        }
                    }
                }
            ],

            'discounts' => 'sometimes|required|array',
            'discounts.*.type' => 'required|string|in:membership,promo-code,instant-discount',
            'discounts.*.discount_amount' => 'required|numeric|min:0',
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
