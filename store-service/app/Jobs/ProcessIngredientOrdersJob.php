<?php

namespace App\Jobs;

use App\Http\Controllers\MarketPurchaseController;
use App\Models\AvailableIngredient;
use App\Models\IngredientOrder;
use App\Services\RabbitMQService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\InputBag;

class ProcessIngredientOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->processIngredientOrders();
        $this->purchaseIngredients();
        $this->processIngredientOrders();
    }

    private function processIngredientOrders()
    {
        $ingredientOrders = IngredientOrder::where('status', '=', 'preparing')->get();
        $availableIngredients = AvailableIngredient::select('ingredient_id', 'available_quantity')->get()->toArray();

        $availableQuantities = [];

        foreach ($availableIngredients as $ingredient) {
            $availableQuantities[$ingredient["ingredient_id"]] = $ingredient["available_quantity"];
        }

        foreach ($ingredientOrders as $ingredientOrder) {
            if (!AvailableIngredient::anyAvailable()) {
                break;
            }

            $finishedOrder = true;
            $ingredients = $ingredientOrder->ingredients()->get();

            foreach ($ingredients as $ingredient) {
                $availableQuantity = $availableQuantities[$ingredient->id];
                $requested_quantity = $ingredient->pivot->requested_quantity - $ingredient->pivot->recieved_quantity;

                if ($requested_quantity != 0) {
                    if ($requested_quantity <= $availableQuantity) {
                        $ingredient->pivot->recieved_quantity += $requested_quantity;
                        $availableQuantity -= $requested_quantity;

                    } else if ($availableQuantity > 0) {
                        $ingredient->pivot->recieved_quantity = $availableQuantity;
                        $availableQuantity = 0;
                        $finishedOrder = false;

                    } else {
                        $finishedOrder = false;
                    }

                    $ingredientOrder->ingredients()->updateExistingPivot($ingredient->id, [
                        "recieved_quantity" => $ingredient->pivot->recieved_quantity
                    ]);

                    $ingredient->save();
                }

                $availableQuantities[$ingredient->id] = $availableQuantity;
            }

            if ($finishedOrder) {
                $ingredientOrder->status = 'finished';
                $ingredientOrder->save();

                $rabbitmq = new RabbitMQService();
                $rabbitmq->publish('{"plate_id": ' . $ingredientOrder->plate_id . '}', 'finished-ingredient-orders-queue', 'kitchen_store', 'finished-ingredient-requests');
            } else {
                $ingredientOrder->save();
            }
        }

        foreach ($availableQuantities as $ingredient_id => $available_quantity) {
            $availableIngredient = AvailableIngredient::where('ingredient_id', $ingredient_id)->first();
            $availableIngredient->available_quantity = $available_quantity;
            $availableIngredient->save();
        }
    }

    private function purchaseIngredients()
    {
        //ProcessMarketPurchaseJob::dispatch()->onQueue("ProcessMarketPurchases");

        // Get preparing orders requested quantity by ingredient id
        $requestedIngredientQuantities = DB::table('ingredient_orders')
            ->join('ingredient_ingredient_order', 'ingredient_orders.id', '=', 'ingredient_ingredient_order.ingredient_order_id')
            ->join('ingredients', 'ingredient_ingredient_order.ingredient_id', '=', 'ingredients.id')
            ->select('ingredients.id', 'ingredients.name', DB::raw('SUM(ingredient_ingredient_order.requested_quantity - ingredient_ingredient_order.recieved_quantity) AS requested_quantity'))
            ->where('ingredient_orders.status', 'preparing')
            ->groupBy('ingredients.id')
            ->havingRaw('SUM(ingredient_ingredient_order.requested_quantity - ingredient_ingredient_order.recieved_quantity) > 0')
            ->get()->toArray();


        if ($requestedIngredientQuantities) {
            //Purchase requested ingredient quantities
            foreach ($requestedIngredientQuantities as $key => $ingredient) {
                $recievedQuantity = 0;
                $requestedQuantity = $ingredient->requested_quantity;

                while ($recievedQuantity != $requestedQuantity) {
                    $maxRetries = 5;

                    for ($retry = 0; $retry < $maxRetries; $retry++) {
                        $response = Http::get("https://recruitment.alegra.com/api/farmers-market/buy?ingredient={$ingredient->name}");
                        if (!$response->successful()) {
                            if ($retry < $maxRetries - 1) {
                                sleep(5);
                            }

                        } else {
                            $boughtQuantity = json_decode($response->body())->quantitySold;
                            if ($boughtQuantity != 0) {
                                $recievedQuantity += $boughtQuantity;

                                if ($recievedQuantity >= $requestedQuantity) {
                                    $recievedQuantity = $requestedQuantity;
                                }

                                $requestedIngredientQuantities[$key]->recieved_quantity = $recievedQuantity;
                                break;
                            }
                        }
                    }
                }
            }

            // Add recieved quantities to MarketPurchase and AvailableIngredients
            $marketPurchaseIngredients = new Request();
            $marketPurchaseIngredients->setJson(new InputBag(['ingredients' => $requestedIngredientQuantities]));

            $marketPuchaseController = new MarketPurchaseController();
            $marketPuchaseController->store($marketPurchaseIngredients);

            foreach ($requestedIngredientQuantities as $ingredient) {
                $availableIngredient = AvailableIngredient::where('ingredient_id', $ingredient->id)->first();
                $availableIngredient->available_quantity = $ingredient->recieved_quantity;
                $availableIngredient->save();
            }
        }
    }
}
