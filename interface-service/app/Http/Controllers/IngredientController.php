<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IngredientController extends Controller
{
    public function index(int $page = 1)
    {
        $response = Http::get("store/api/available-ingredients?page={$page}");

        if (!$response->successful()) {
            return back($response->getStatusCode())->withErrors(['error' => 'Hubo un problema al obtener los ingredientes, espera un momento e inténtalo de nuevo']);
        }

        $ingredients = $response->json();

        if (!$ingredients["data"]) {
            return abort(404);
        }

        return view('ingredients', ['ingredients' => $ingredients]);
    }
}
