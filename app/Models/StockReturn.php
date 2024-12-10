<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'quantity',
        'reason',
        'return_type',
        'returned_at',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
