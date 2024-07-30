<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'status', 'booking_from', 'booking_to',
        'booked_by', 'company_id', 'branch_id'
    ];
}
