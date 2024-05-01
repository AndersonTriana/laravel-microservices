<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableIngredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id',
        'available_quantity'
    ];

    public function ingredient() {
        return $this->hasOne(Ingredient::class);
    }

    public static function anyAvailable(): bool
    {
        return static::where('available_quantity', '>', 0)->exists();
    }
}
