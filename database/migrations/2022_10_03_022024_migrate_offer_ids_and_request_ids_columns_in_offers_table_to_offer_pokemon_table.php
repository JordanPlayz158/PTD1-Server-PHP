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
        $i = 1;
        foreach(DB::table('offers')->select(['id', 'offerIds', 'requestIds'])->lazyById() as $offer) {
            $offerIds = explode(',', $offer->offerIds);

            foreach($offerIds as $offerId) {
                DB::table('offer_pokemon')->insert([
                    'id' => $i,
                    'pokemon_id' => $offerId
                ]);
            }

            DB::table('offers')->where('id', '=', $offer->id)->update(['offerIds' => $i]);

            // Increment before dealing with requestIds
            $i++;

            DB::table('offer_pokemon')->insert([
                'id' => $i,
                'pokemon_id' => $offer->requestIds
            ]);

            DB::table('offers')->where('id', '=', $offer->id)->update(['requestIds' => $i]);

            $i++;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $string = '';
        $previousId = 1;
        foreach(DB::table('offer_pokemon')->lazyById() as $offer) {
            $id = $offer->id;

            if($id != $previousId) {
                $offerBuilder = DB::table('offers')->where('offerIds', '=', $id);

                if($offerBuilder->count() === 1) {
                    $offerBuilder->update(['offerIds' => substr($string, 0, -1)]);
                } else {
                    DB::table('offers')
                        ->where('requestIds', '=', $id)
                        ->update(['requestIds' => substr($string, 0, -1)]);
                }

                $string = '';
            }

            $string .= $offer->pokemon_id . ',';

            $previousId = $id;
        }
    }
};
