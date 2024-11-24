<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'price',
        'menu_item_id',
    ];

    function menu_item(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id', 'id');
    }
}
