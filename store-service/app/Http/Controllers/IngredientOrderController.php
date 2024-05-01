<?php

namespace App\Http\Controllers;

use App\Http\Resources\IngredientOrderResource;
use App\Http\Resources\IngredientResource;
use App\Models\IngredientOrder;
use Illuminate\Http\Request;

class IngredientOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return IngredientOrderResource::collection(IngredientOrder::paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ingredientController = new IngredientController();
        $ingredientOrder = new IngredientOrder();

        $request = json_decode($request->json());
        $ingredientOrder->plate_id = $request->plate_id;
        $ingredientOrder->save();

        foreach ($request->ingredients as $ingredient) {
            $ingredientInDB = $ingredientController->getIngredientByName($ingredient->name);

            if (!$ingredientInDB) {
                $ingredientInDB = $ingredientController->store($ingredient->name);
            }

            $ingredientInDB = $ingredientInDB->id;

            $ingredientOrder->ingredients()->attach($ingredientInDB, [
                'requested_quantity' => $ingredient->quantity,
                'recieved_quantity' => 0
            ]);
        }

        $ingredientOrder->save();

        return new IngredientOrderResource($ingredientOrder);
    }

    /**
     * Display the specified resource.
     */
    public function show(IngredientOrder $ingredientOrder)
    {
        return new IngredientOrderResource($ingredientOrder);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IngredientOrder $ingredientOrder)
    {
        $ingredientOrder->update($request->only(["name"]));

        return new IngredientOrderResource($ingredientOrder);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IngredientOrder $ingredientOrder)
    {
        $ingredientOrder->delete();

        return response()->json(null, 204);
    }
}
