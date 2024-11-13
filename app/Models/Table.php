<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'max_seat',
        'min_seat',
        'status',
        'booking_from',
        'booking_to',
        'floor_id',
        'branch_id',
        'request_by',
        'received_by'
    ];
}
