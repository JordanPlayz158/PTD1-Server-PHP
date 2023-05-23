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
        Schema::table('giveaway_entries', function (Blueprint $table) {
            $table->dropForeign('giveaway_entries_giveaway_id_foreign');
            $table->dropForeign('giveaway_entries_save_id_foreign');
        });

        Schema::table('giveaway_entries', function (Blueprint $table) {
            $table->foreign('giveaway_id')->references('id')->on('giveaways')->cascadeOnDelete();
            $table->foreign('save_id')->references('id')->on('saves')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('giveaway_entries', function (Blueprint $table) {
            $table->dropForeign('giveaway_entries_giveaway_id_foreign');
            $table->dropForeign('giveaway_entries_save_id_foreign');
        });

        Schema::table('giveaway_entries', function (Blueprint $table) {
            $table->foreign('giveaway_id')->references('id')->on('giveaways');
            $table->foreign('save_id')->references('id')->on('saves');
        });
    }
};
