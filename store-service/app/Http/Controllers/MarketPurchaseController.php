<?php

namespace App\Http\Controllers;

use App\Http\Resources\MarketPurchaseResource;
use App\Models\MarketPurchase;
use Illuminate\Http\Request;

class MarketPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MarketPurchaseResource::collection(MarketPurchase::paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ingredients = $request->json()->all()["ingredients"];

        $marketPurchase = new MarketPurchase();
        $marketPurchase->save();

        foreach ($ingredients as $ingredient) {
            if ($ingredient->requested_quantity != 0) {
                $marketPurchase->ingredients()->attach($ingredient->id, [
                    'requested_quantity' => $ingredient->requested_quantity,
                    'recieved_quantity' => $ingredient->recieved_quantity
                ]);
            }
        }

        $marketPurchase->save();

        return new MarketPurchaseResource($marketPurchase);
    }

    /**
     * Display the specified resource.
     */
    public function show(MarketPurchase $marketPurchase)
    {
        return new MarketPurchaseResource($marketPurchase);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MarketPurchase $marketPurchase)
    {
        $marketPurchase->update($request->only(["name"]));

        return new MarketPurchaseResource($marketPurchase);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MarketPurchase $marketPurchase)
    {
        $marketPurchase->delete();

        return response()->json(null, 204);
    }

    public function getMarketPurchases()
    {
        return MarketPurchaseResource::collection(
            MarketPurchase::with([
                'ingredients' => function ($query) {
                    $query->select('ingredients.id', 'ingredients.name');
                }
            ])->orderBy('id', 'desc')->select('id', 'created_at')->paginate(4)
        );
    }

}
