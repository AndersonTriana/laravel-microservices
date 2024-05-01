<?php

use App\Http\Controllers\IngredientController;
use App\Http\Controllers\MarketPurchaseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('createOrder');
})->name('createOrder');

Route::post('/new-order', [OrderController::class, 'store']);

Route::get('/orders/{page?}/{status?}', [OrderController::class, 'index'])->name('orders');

Route::get('/ingredients/{page?}', [IngredientController::class, 'index'])->name('ingredients');

Route::get('/purchases/{page?}', [MarketPurchaseController::class, 'index'])->name('marketPurchases');

Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes');