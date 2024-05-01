<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ingredient_market_purchase', function (Blueprint $table) {
            $table->foreignId('market_purchase_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->integer('requested_quantity')->unsigned();
            $table->integer('recieved_quantity')->unsigned();
            $table->timestamps();

            $table->primary(['ingredient_id', 'market_purchase_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_market_purchase');
    }
};
