<?php

namespace App\Console\Commands;

use App\Http\Controllers\IngredientOrderController;
use Illuminate\Console\Command;
use App\Services\RabbitMQService;
use Illuminate\Http\Request;

class MQConsumerIngredientRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume the rabbitmq ingredient-orders-queue queue';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $saveIngredientOrder = function ($msg) {
            //Handle message
            $ingredientOrderController = new IngredientOrderController();
            $ingredientOrderData = new Request();
            $ingredientOrderData->setJson($msg->body);

            $ingredientOrderController->store($ingredientOrderData);
        };

        $mqService = new RabbitMQService();
        $mqService->consume($saveIngredientOrder, 'ingredient-orders-queue', 'kitchen_store', 'ingredient-requests');
    }
}