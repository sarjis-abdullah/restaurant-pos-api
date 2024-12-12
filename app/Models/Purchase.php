<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'purchase_date',
        'total_amount',
        'discount_amount',
        'tax_amount',
        'shipping_cost',
        'status'
    ];
    public function payments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Payment::class, 'payable', 'payable_type', 'payable_id');
    }
    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supplier::class);
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
