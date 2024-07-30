<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    protected $fillable = [
        'max_seat', 'min_seat', 'status', 'booking_from',
        'booking_to', 'booked_by', 'floor_id', 'company_id',
        'branch_id'
    ];
}
