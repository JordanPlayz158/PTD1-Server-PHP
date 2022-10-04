<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach(DB::table('offer_pokemon')->lazyById() as $offer) {
            if(DB::table('pokemon')->where('id', '=', $offer->pokemon_id)->count() === 0) {
                Log::info('Deleting offer with invalid pokemon id', [$offer]);
                DB::table('offer_pokemon')->where('id', '=', $offer->id)->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
