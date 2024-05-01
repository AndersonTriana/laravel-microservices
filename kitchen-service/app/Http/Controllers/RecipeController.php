<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Services\RabbitMQService;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RecipeResource::collection(Recipe::paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return new RecipeResource(Recipe::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        return new RecipeResource($recipe);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        $recipe->update($request->only(["name"]));

        return new RecipeResource($recipe);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return response()->json(null, 204);
    }

    public function getRecipesWithIngredients() {
        return RecipeResource::collection(
            Recipe::with([
                'ingredients' => function ($query) {
                    $query->select('ingredients.id', 'ingredients.name', 'ingredient_recipe.quantity');
                }
            ])->select('id', 'name')->paginate(24)
        );
    }
}
