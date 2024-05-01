<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ingredients')->insert([
            ['name' => 'tomato'],
            ['name' => 'lemon'],
            ['name' => 'potato'],
            ['name' => 'rice'],
            ['name' => 'ketchup'],
            ['name' => 'lettuce'],
            ['name' => 'onion'],
            ['name' => 'cheese'],
            ['name' => 'meat'],
            ['name' => 'chicken']
        ]);
    }
}
