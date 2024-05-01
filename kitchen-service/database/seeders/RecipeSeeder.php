<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('recipes')->insert([
            ['name' => 'Pollo al Limón con Arroz'],
            ['name' => 'Ensalada César de Pollo'],
            ['name' => 'Picada con Queso Fundido'],
            ['name' => 'Arroz Frito con Vegetales con Carne'],
            ['name' => 'Hamburguesa de Pollo'],
            ['name' => 'Sopa de Pollo']
        ]);
    }
}
