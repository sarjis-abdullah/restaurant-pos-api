<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'status', 'order_by', 'table_id', 'menu_item_id',
        'type', 'pickup_date', 'company_id', 'branch_id', 'received_by', 'prepare_by'
    ];

    function taken_by()
    {
        return $this->belongsTo(User::class, 'order_by', 'id');
    }
    function order_by()
    {
        return $this->belongsTo(User::class, 'order_by', 'id');
    }
    function prepare_by()
    {
        return $this->belongsTo(User::class, 'prepare_by', 'id');
    }
    function received_by()
    {
        return $this->belongsTo(User::class, 'received_by', 'id');
    }
}
