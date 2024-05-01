<?php

namespace App\Jobs;

use App\Http\Controllers\PlateOrderController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPendingPlateOrders implements ShouldQueue
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
        $plateOrderController = new PlateOrderController();

        $pendingPlateOrders = $plateOrderController->getPendingPlateOrders();

        $pendingPlateOrders->each(function ($plateOrder) {
            echo " - ". $plateOrder->id ." - \n";
        });
    }
}
