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
        Schema::create('giveaway_pokemon', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('pokemon_id');
            $table->timestamps();

            $table->foreign('pokemon_id')->references('id')->on('pokemon')->cascadeOnDelete();
            $table->primary(['id', 'pokemon_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('giveaway_pokemon');
    }
};
