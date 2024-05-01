<?php

namespace App\Console\Commands;

use App\Models\PlateOrder;
use Illuminate\Console\Command;
use App\Services\RabbitMQService;

class MQConsumerFinishedIngredientOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume-finished';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume the rabbitmq finished-ingredient-orders-queue';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        $saveIngredientOrder = function ($msg) {
            //Handle message
            $plateOrder = PlateOrder::where('id', json_decode($msg->body)->plate_id)->first();
            $plateOrder->update(['status' => 'finished']);
            $order_id = $plateOrder->order_id;

            $mqService = new RabbitMQService();
            $mqService->publish('{"order_id": ' . $order_id . '}', 'finished-orders-queue', 'order-management_kitchen', 'finished-order');
        };

        $mqService = new RabbitMQService();
        $mqService->consume($saveIngredientOrder, 'finished-ingredient-orders-queue', 'kitchen_store', 'finished-ingredient-requests');

    }
}