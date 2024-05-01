<?php

namespace App\Http\Controllers;

use App\Http\Resources\AvailableIngredientResource;
use App\Models\AvailableIngredient;
use Illuminate\Http\Request;

class AvailableIngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AvailableIngredientResource::collection(AvailableIngredient::paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return new AvailableIngredientResource(AvailableIngredient::create($request->only("", "")));
    }

    /**
     * Display the specified resource.
     */
    public function show(AvailableIngredient $availableIngredient)
    {
        return new AvailableIngredientResource($availableIngredient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AvailableIngredient $availableIngredient)
    {
        $availableIngredient->update($request->only(["name"]));

        return new AvailableIngredientResource($availableIngredient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AvailableIngredient $availableIngredient)
    {
        $availableIngredient->delete();

        return response()->json(null, 204);
    }
}
