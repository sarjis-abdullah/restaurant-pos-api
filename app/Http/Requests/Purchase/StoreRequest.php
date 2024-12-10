<?php

namespace App\Http\Requests\Purchase;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'purchaseProducts' => ['required', 'array', 'min:1'],
            'purchaseProducts.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'purchaseProducts.*.sku' => ['nullable', 'string', 'max:50'],
            'purchaseProducts.*.quantity' => ['required', 'integer', 'min:1'],
            'purchaseProducts.*.purchase_price' => ['required', 'numeric', 'min:0'],
            'purchaseProducts.*.selling_price' => ['required', 'numeric', 'min:0'],
            'purchaseProducts.*.tax_amount' => ['required', 'numeric', 'min:0'],
            'purchaseProducts.*.tax_type' => ['required', 'in:flat,percentage'],
            'purchaseProducts.*.discount_amount' => ['required', 'numeric', 'min:0'],
            'purchaseProducts.*.discount_type' => ['required', 'in:flat,percentage'],

            'shipping_cost' => ['required', 'numeric', 'min:0'],
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'purchase_date' => ['required', 'date', 'date_format:d-m-Y'],

            'payment.amount' => ['required', 'numeric', 'min:0'],
            'payment.method' => ['required', 'in:cash,bank'],
        ];
    }
}
