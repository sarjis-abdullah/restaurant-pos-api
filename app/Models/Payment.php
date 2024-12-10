<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'created_by',
        'received_by',
        'type',
        'payable_type',
        'payable_id',
        'status',
        'amount',
        'round_off_amount',
        'method',
        'reference_number',
        'transaction_number',
        'transaction_id',
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
    public function payable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
