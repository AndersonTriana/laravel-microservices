<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecipeIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = Recipe::all();

        foreach ($recipes as $recipe) {
            $attachedIngredients = $recipe->ingredients->pluck('id')->toArray();

            for ($i = 0; $i < mt_rand(2, 5); $i++) {
                $random = mt_rand(1, 4);
                $ingredient = Ingredient::inRandomOrder()
                    ->whereNotIn('id', $attachedIngredients)
                    ->whereDoesntHave('recipes', function ($query) use ($recipe) {
                        $query->where('recipe_id', $recipe->id);
                    })
                    ->first();

                if ($ingredient) {
                    $recipe->ingredients()->attach($ingredient, ['quantity' => rand(1, 3)]);
                    $attachedIngredients[] = $ingredient->id;
                }
            }
        }
    }
}
