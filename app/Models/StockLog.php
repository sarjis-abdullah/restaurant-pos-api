<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','stock_id', 'type', 'prev_quantity', 'new_quantity', 'reference_id', 'reason'];
}
