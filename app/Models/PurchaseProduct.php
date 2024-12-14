<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'stock_id',
        'quantity',
        'purchase_price',
        'selling_price',
        'tax_amount',
        'tax_type',
        'discount_type',
        'discount_amount',
        'allocated_shipping_cost',
        'subtotal'
    ];

    function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
    function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
