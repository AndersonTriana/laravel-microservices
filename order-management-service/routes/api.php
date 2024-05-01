<?php

use App\Http\Controllers\OrderController;
use App\Services\RabbitMQService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('create-order', [OrderController::class, 'store']);

Route::apiResource('orders', OrderController::class);

Route::get('orders-preparing/{page?}', [OrderController::class, 'getPreparing']);
Route::get('orders-finished/{page?}', [OrderController::class, 'getFinished']);
