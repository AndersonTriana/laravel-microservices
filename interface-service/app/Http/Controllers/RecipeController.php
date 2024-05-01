<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecipeController extends Controller
{
    public function index(int $page = 1)
    {
        $response = Http::get("laravel.kitchen/api/recipes?page={$page}");

        if (!$response->successful()) {
            return back($response->getStatusCode())->withErrors(['error' => 'Hubo un problema al obtener las recetas, espera un momento e inténtalo de nuevo']);
        }

        $ingredients = $response->json();

        if (!$ingredients["data"]) {
            return abort(404);
        }

        return view('recipes', ['recipes' => $ingredients]);
    }
}
