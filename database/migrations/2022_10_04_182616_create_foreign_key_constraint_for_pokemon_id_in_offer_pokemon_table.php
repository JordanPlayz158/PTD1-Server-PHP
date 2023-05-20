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
        Schema::table('offer_pokemon', function (Blueprint $table) {
            $table->foreign('pokemon_id', 'FK_pokemon_id_offer_pokemon')
                ->references('id')
                ->on('pokemon')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_pokemon', function (Blueprint $table) {
            $table->dropForeign('FK_pokemon_id_offer_pokemon');
        });
    }
};
