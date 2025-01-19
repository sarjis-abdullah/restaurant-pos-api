<?php

namespace App\Http\Requests\Purchase;

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
            'purchaseProducts' => ['required', 'array', 'min:1'],
            'purchaseProducts.*.product_id' => ['required', 'integer', 'exists:products,id'],
//            'purchaseProducts.*.stock_id' => ['required_without:purchaseProducts.*.sku', 'exists:stocks,id'],
            'purchaseProducts.*.sku' => [
                'required_without:purchaseProducts.*.stockId',
            ],
            'purchaseProducts.*.quantity' => ['required', 'integer', 'min:1'],
            'purchaseProducts.*.purchase_price' => ['required', 'numeric', 'min:0'],
            'purchaseProducts.*.selling_price' => ['required', 'numeric', 'min:0'],
            'purchaseProducts.*.cost_per_unit' => ['required', 'numeric', 'min:0'],
            'purchaseProducts.*.tax' => ['required', 'numeric', 'min:0'],
            'purchaseProducts.*.tax_type' => ['required', 'in:flat,percentage'],
            'purchaseProducts.*.discount' => ['required', 'numeric', 'min:0'],
            'purchaseProducts.*.discount_type' => ['required', 'in:flat,percentage'],
            'purchaseProducts.*.expire_date' => ['required'],

            'shipping_cost' => ['required', 'numeric', 'min:0'],
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'purchase_date' => ['required', 'date', 'date_format:Y-m-d'],
            'status' => ['required'],
            'branch_id' => ['required'],

            'payment' => ['required'],
            'payment.amount' => ['required', 'numeric', 'min:0'],
            'payment.method' => ['required', 'in:bkash,nagad,cash,bank'],
        ];
    }
}
