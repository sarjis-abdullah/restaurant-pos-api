<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'contact_number', 'status', 'address', 'type',
        'details', 'company_id'
    ];

    function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
