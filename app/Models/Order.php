<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'total_discount',
        'total_tax',
        'total_price',
        'table_id',
        'type',
        'order_date',
        'pickup_date',
        'branch_id',
        'prepare_by',
        'created_by',
        'order_by'
    ];
    function order_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'order_by', 'id');
    }
    function prepare_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepare_by', 'id');
    }
    function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    function order_items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
