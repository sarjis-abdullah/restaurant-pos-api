<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'purchase_date',
        'total_amount',
        'discount',
        'tax',
        'shipping_cost',
        'status',
        'branch_id',
    ];
    public function payments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Payment::class, 'payable', 'payable_type', 'payable_id');
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    function purchaseProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id', 'id');
    }

    public function getDueAmountAttribute()
    {
        // Sum of successful/completed payments
        $paidAmount = $this->payments()
            ->where('status', 'completed') // Assuming 'completed' or 'success' indicates successful payments
            ->sum('amount');

        // Calculate due amount
        return $this->total_amount - $paidAmount;
    }
}
