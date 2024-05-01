<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MarketPurchaseController extends Controller
{
    public function index(int $page = 1)
    {
        $response = Http::get("laravel.store/api/market-purchases?page={$page}");

        if (!$response->successful()) {
            return back($response->getStatusCode())->withErrors(['error' => 'Hubo un problema al obtener los marketPurchasees, espera un momento e inténtalo de nuevo']);
        }

        $marketPurchases = $response->json();

        if (!$marketPurchases["data"]) {
            return abort(404);
        }

        return view('marketPurchases', ['marketPurchases' => $marketPurchases]);
    }
}
