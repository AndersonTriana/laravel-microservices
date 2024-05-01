<?php

namespace App\Console\Commands;

use App\Http\Controllers\PlateOrderController;
use Illuminate\Console\Command;
use App\Services\RabbitMQService;

class MQConsumerCreatePlateOrder extends Command
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
    protected $description = 'Consume the rabbitmq plate-order-queue';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $savePlateOrder = function ($msg) {
            //Handle message
            $order_id = json_decode($msg->body)->id;
            $plateOrderController = new PlateOrderController();
            $plateOrderController->store($order_id);
        };

        $mqService = new RabbitMQService();
        $mqService->consume($savePlateOrder, 'plate-orders-queue', 'order-management_kitchen', 'plate-request');
    }
}