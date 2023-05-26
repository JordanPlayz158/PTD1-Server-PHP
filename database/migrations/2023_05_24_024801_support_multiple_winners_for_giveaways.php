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
            $table->boolean('winner')->nullable(false)->default(false);
        });

        foreach(DB::table('giveaways')->lazyById() as $giveaway) {
            DB::table('giveaway_entries')
                ->where('giveaway_id', '=', $giveaway->id)
                ->where('save_id', '=', $giveaway->winner_save_id)
                ->update(['winner' => true]);
        }

        Schema::dropColumns('giveaways', 'winner_save_id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('giveaways', function (Blueprint $table) {
            $table->unsignedBigInteger('winner_save_id')->nullable()->after('owner_save_id');
        });

        foreach(DB::table('giveaway_entries')->lazyById() as $giveawayEntry) {
            if($giveawayEntry->winner === false) continue;

            DB::table('giveaways')
                ->where('id', '=', $giveawayEntry->giveaway_id)
                ->update(['winner_save_id' => $giveawayEntry->save_id]);
        }

        Schema::dropColumns('giveaway_entries', 'winner');
    }
};
