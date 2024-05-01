<?php

namespace App\Http\Controllers;

use App\Services\RabbitMQService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function index(int $page = 1, string $status = "")
    {
        $url = '';

        if ($status == 'finished') {
            $url = "laravel.order-manager/api/orders-finished?page={$page}";
        } else if ($status == 'preparing') {
            $url = "laravel.order-manager/api/orders-preparing?page={$page}";
        } else {
            $url = "laravel.order-manager/api/orders?page={$page}";
        }

        $response = Http::get($url);

        if (!$response->successful()) {
            return back($response->getStatusCode())->withErrors(['error' => 'Hubo un problema al obtener las ordenes, espera un momento e inténtalo de nuevo']);
        }

        return view('orders', ['orders' => $response->json(), 'status' => $status]);
    }

    public function store(?Request $request)
    {
        $orderQuantity = $request->all()['orderQuantity'];
        $response = Http::accept('application/json')->post(
            'laravel.order-manager/api/create-order',
            ['orderQuantity' => $orderQuantity]
        );

        if ($response->getStatusCode() != 201) {
            return back($response->getStatusCode())->withErrors(['error', 'Hubo un problema al crear tu orden, espera un momento e inténtalo de nuevo.']);
        }
        /*   
                //Using queue insead http
                $rabbitmq = new RabbitMQService();
                $rabbitmq->publish('create order', 'orders-queue', 'interface_order-management', 'order-requests');
         */

        if ($orderQuantity > 1) {
            return back()->with('message', "Tus $orderQuantity ordenes se están creando");
        }

        return back()->with('message', 'Orden creada correctamente');
    }
}
