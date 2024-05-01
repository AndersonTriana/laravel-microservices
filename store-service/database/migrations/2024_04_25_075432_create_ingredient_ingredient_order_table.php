<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ingredient_ingredient_order', function (Blueprint $table) {
            $table->foreignId('ingredient_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->integer('requested_quantity')->unsigned();
            $table->integer('recieved_quantity')->unsigned();
            $table->timestamps();

            $table->primary(['ingredient_id', 'ingredient_order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_ingredient_order');
    }
};
