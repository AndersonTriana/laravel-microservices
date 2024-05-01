<?php

use App\Http\Controllers\AvailableIngredientController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\IngredientOrderController;
use App\Http\Controllers\MarketPurchaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('available-ingredients', [IngredientController::class, 'getAvailableQuantities']);

Route::get('market-purchases', [MarketPurchaseController::class, 'getMarketPurchases']);