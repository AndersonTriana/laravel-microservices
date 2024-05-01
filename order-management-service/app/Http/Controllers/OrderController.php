<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Jobs\CreateOrdersJob;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return OrderResource::collection(Order::orderBy('id', 'desc')->paginate(24));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        CreateOrdersJob::dispatch($request->input("orderQuantity"))->onQueue("createOrders");

        return response('', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $order->update($request->only(["status"]));

        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(null, 204);
    }

    public function getPreparing()
    {
        return OrderResource::collection(Order::where('status', '=', 'preparing')->paginate(24));
    }

    public function getFinished()
    {
        return OrderResource::collection(Order::where('status', '=', 'finished')->orderBy('id', 'desc')->paginate(24));
    }
}
