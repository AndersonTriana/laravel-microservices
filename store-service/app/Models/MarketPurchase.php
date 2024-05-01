<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'status'
    ];

    public function ingredients() {
        return $this->belongsToMany(Ingredient::class)->withPivot('requested_quantity', 'recieved_quantity');
    }
}
