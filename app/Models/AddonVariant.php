<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddonVariant extends Model
{
    use HasFactory;
    protected $fillable = ['addon_id', 'type', 'name', 'price', 'recipe_id'];

    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class);
    }

    function variant(): BelongsTo
    {
        return $this->belongsTo(AddonVariant::class, 'variant_id', 'id');
    }
    function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'id');
    }

}
