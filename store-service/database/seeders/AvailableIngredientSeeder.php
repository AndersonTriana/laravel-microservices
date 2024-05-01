<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AvailableIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = Ingredient::all();
        $available_ingredients = [];

        foreach ($ingredients as $ingredient) {
            $available_ingredients[] = [
                    'ingredient_id' => $ingredient->id,
                    'available_quantity' => 5
            ];
        }

        DB::table('available_ingredients')->insert($available_ingredients);
    }
}
