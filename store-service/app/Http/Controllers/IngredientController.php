<?php

namespace App\Http\Controllers;

use App\Http\Resources\IngredientResource;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return IngredientResource::collection(Ingredient::paginate(24));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $name)
    {
        return new IngredientResource(Ingredient::create(["name" => $name]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredient $ingredient)
    {
        return new IngredientResource($ingredient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $ingredient->update($request->only(["name"]));

        return new IngredientResource($ingredient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return response()->json(null, 204);
    }

    public function getAvailableQuantities()
    {
        return IngredientResource::collection(
            Ingredient::with([
                'availableQuantity' => function ($query) {
                    $query->select('ingredient_id', 'available_quantity');
                }
            ])->select('id', 'name')->paginate(24)
        );
    }

    public function getIngredientByName(string $name)
    {
        $ingredient = Ingredient::where("name", $name)->first();

        if (!$ingredient) {
            return null;
        }

        return new IngredientResource($ingredient);
    }
}
