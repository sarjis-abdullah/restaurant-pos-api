<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'status', 'branch_id'
    ];
    function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    function menu_items(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'menu_id', 'id');
    }
}
