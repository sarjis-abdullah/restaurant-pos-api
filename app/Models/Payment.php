<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'created_by', 'received_by', 'order_id', 'status',
        'total_amount', 'paid_amount', 'method',
        'reference_number', 'transaction_number',
        'branch_id'
    ];

    function paid_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    function received_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by', 'id');
    }
}
