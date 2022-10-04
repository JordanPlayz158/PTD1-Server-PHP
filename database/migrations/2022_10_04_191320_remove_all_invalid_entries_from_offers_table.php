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
        foreach(DB::table('offers')->select(['id', 'offer_pokemon', 'request_pokemon'])->lazyById() as $offer) {
            if(DB::table('offer_pokemon')->where('id', '=', $offer->offer_pokemon)->count() === 0
            || DB::table('offer_pokemon')->where('id', '=', $offer->request_pokemon)->count() === 0) {
                Log::info('Deleting offer with invalid offer_pokemon id', [$offer]);
                DB::table('offers')->where('id', '=', $offer->id)->delete();
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
