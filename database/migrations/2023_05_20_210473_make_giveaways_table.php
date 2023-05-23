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
        Schema::create('giveaways', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->unsignedBigInteger('owner_save_id');
            $table->unsignedBigInteger('giveaway_pokemon_id')->unique();
            $table->timestamps();
            $table->timestamp('complete_at')->useCurrent();

            $table->foreign('owner_save_id')->references('id')->on('saves')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('giveaways');
    }
};
