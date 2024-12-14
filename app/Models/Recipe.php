<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'name', 'category', 'preparation_time', 'serves'];
    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
