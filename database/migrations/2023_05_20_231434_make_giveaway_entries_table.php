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
        Schema::create('giveaway_entries', function (Blueprint $table) {
            $table->unsignedBigInteger('giveaway_id');
            $table->unsignedBigInteger('save_id');

            $table->foreign('giveaway_id')->references('id')->on('giveaways');
            $table->foreign('save_id')->references('id')->on('saves');
            $table->primary(['giveaway_id', 'save_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('giveaway_entries');
    }
};
