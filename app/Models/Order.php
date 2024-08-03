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
        'status', 'table_id', 'menu_item_id',
        'type', 'pickup_date', 'company_id', 'branch_id',
        'received_by', 'prepare_by', 'taken_by', 'order_by'
    ];
    function order_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'order_by', 'id');
    }
    function prepare_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepare_by', 'id');
    }
    function received_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by', 'id');
    }
    function taken_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'taken_by', 'id');
    }
    function order_items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
