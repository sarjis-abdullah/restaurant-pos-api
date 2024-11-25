<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'quantity',
        'category_id',
        'menu_id',
        'discount_id',
        'tax_id',
        'tax_included',
        'type',
        'description',
        'ingredients',
        'preparation_time',
        'serves',
        'allow_other_discount',
    ];

    function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'id');
    }
    function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id', 'id');
    }
    function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
    function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    function variants(): HasMany
    {
        return $this->hasMany(Variant::class, 'menu_item_id', 'id');
    }
    function addons(): HasMany
    {
        return $this->hasMany(Addon::class, 'menu_item_id', 'id');
    }
}
