<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function ingredientOrders() {
        return $this->belongsToMany(IngredientOrder::class)->withPivot('requested_quantity', 'recieved_quantity');
    }

    public function marketPurchases() {
        return $this->belongsToMany(MarketPurchase::class)->withPivot('requested_quantity', 'recieved_quantity');
    }

    public function availableQuantity() {
        return $this->hasOne(AvailableIngredient::class);
    }
}
