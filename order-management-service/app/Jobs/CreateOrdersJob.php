<?php

namespace App\Jobs;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\RabbitMQService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $orderQuantity;

    /**
     * Create a new job instance.
     */
    public function __construct(int $orderQuantity)
    {
        $this->orderQuantity = $orderQuantity;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rabbitmq = new RabbitMQService();

        for ($i=0; $i < $this->orderQuantity; $i++) { 
            $order = new OrderResource(Order::create());
            $rabbitmq->publish($order->toJson(), 'plate-orders-queue', 'order-management_kitchen', 'plate-request');
        }
    }
}
