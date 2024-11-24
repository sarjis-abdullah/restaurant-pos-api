<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddonVariation extends Model
{
    use HasFactory;

    protected $fillable = ['addon_id', 'attribute', 'value', 'price_modifier'];

    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class);
    }
}
