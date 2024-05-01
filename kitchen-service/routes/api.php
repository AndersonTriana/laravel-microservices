<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::get('recipes', [RecipeController::class, 'getRecipesWithIngredients']);