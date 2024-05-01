<?php

namespace App\Http\Controllers;

use App\Http\Resources\IngredientResource;
use App\Http\Resources\PlateOrderResource;
use App\Models\PlateOrder;
use App\Models\Recipe;
use App\Services\RabbitMQService;
use Illuminate\Http\Request;

class PlateOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PlateOrderResource::collection(PlateOrder::paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(int $order_id)
    {
        $recipe = Recipe::inRandomOrder()->first();

        $plateOrder = PlateOrder::create([
            'order_id' => $order_id,
            'recipe_id' => $recipe->id
        ]);

        $ingredients = IngredientResource::collection($recipe->ingredients()->get());

        $ingredientsRequest = [];

        foreach ($ingredients as $ingredient) {
            $ingredientsRequest[] = [
                'name' => $ingredient->name,
                'quantity' => $ingredient->pivot->quantity
            ];
        }

        $ingredientsRequest = json_encode(['plate_id' => $plateOrder->id, 'ingredients' => $ingredientsRequest]);

        $rabbitmq = new RabbitMQService();
        $rabbitmq->publish($ingredientsRequest, 'ingredient-orders-queue', 'kitchen_store', 'ingredient-requests');

        return $plateOrder;
    }

    /**
     * Display the specified resource.
     */
    public function show(PlateOrder $plateOrder)
    {
        return new PlateOrderResource($plateOrder);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlateOrder $plateOrder)
    {
        $plateOrder->update($request->only(["status"]));

        return new PlateOrderResource($plateOrder);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlateOrder $plateOrder)
    {
        $plateOrder->delete();

        return response()->json(null, 204);
    }

    public function getPendingPlateOrders()
    {
        return PlateOrderResource::collection(PlateOrder::where("status", "preparing")->get());
    }
}
