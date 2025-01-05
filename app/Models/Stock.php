<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'product_id',
        'quantity',
        'unit_cost',
        'purchase_price',
        'selling_price',
        'tax',
        'tax_type',
        'discount_type',
        'discount',
        'shipping_cost',
    ];

}
