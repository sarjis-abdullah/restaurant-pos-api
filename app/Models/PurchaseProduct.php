<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
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

}
