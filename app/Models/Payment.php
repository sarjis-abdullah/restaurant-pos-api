<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'created_by', 'received_by', 'order_id', 'status',
        'total_amount', 'paid_amount', 'method',
        'reference_number', 'transaction_number',
        'company_id', 'branch_id'
    ];
}
