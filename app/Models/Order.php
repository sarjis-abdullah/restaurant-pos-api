<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'status', 'order_by', 'table_id', 'menu_item_id',
        'type', 'pickup_date', 'company_id', 'branch_id'
    ];
}
