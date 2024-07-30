<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'contact_number', 'status', 'address', 'type',
        'details', 'company_id'
    ];
}
