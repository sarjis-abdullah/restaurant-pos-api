<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'addon_id',
        'variant_id',
        'quantity',
        'total_amount',
    ];

    function order_item(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'id');
    }
    function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class, 'addon_id', 'id');
    }
    function variant(): BelongsTo
    {
        return $this->belongsTo(AddonVariant::class, 'variant_id', 'id');
    }
}
