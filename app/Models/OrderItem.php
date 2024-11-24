<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'quantity',
        'total_price',
        'variant_id',
        'discount_amount',
        'tax_amount',
    ];

    function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    function menu_item(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id', 'id');
    }
    function variant(): BelongsTo
    {
        return $this->belongsTo(Variation::class, 'variant_id', 'id');
    }
    function order_item_addons(): HasMany
    {
        return $this->hasMany(OrderItemAddon::class, 'order_item_id', 'id');
    }
}
