<?php

namespace App\Console\Commands;

use App\Http\Controllers\OrderController;
use App\Models\Order;
use Illuminate\Console\Command;
use App\Services\RabbitMQService;

class MQConsumerFinishedOrders extends Command
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
    protected $description = 'Consume the mq finished-orders-queue';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $savePlateOrder = function ($msg) {
            //Handle message
            $order = Order::where('id', json_decode($msg->body)->order_id)->first();
            $order->update(['status' => 'finished']);
        };

        $mqService = new RabbitMQService();
        $mqService->consume($savePlateOrder, 'finished-orders-queue', 'order-management_kitchen', 'finished-order');
    }
}