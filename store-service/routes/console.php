<?php

use App\Jobs\ProcessIngredientOrdersJob;
use App\Jobs\ProcessMarketPurchaseJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('process-orders-job', function () {
    ProcessIngredientOrdersJob::dispatch()->onQueue("ProcessIngredientOrders");
});

Schedule::job(new ProcessIngredientOrdersJob, 'ProcessIngredientOrders')->withoutOverlapping()->everyMinute();