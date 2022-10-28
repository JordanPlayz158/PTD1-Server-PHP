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
        Schema::table('offers', function (Blueprint $table) {
            $table->renameColumn('offer_pokemon', 'offer_pokemon_id');
            $table->renameColumn('request_pokemon', 'request_pokemon_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->renameColumn('offer_pokemon_id', 'offer_pokemon');
            $table->renameColumn('request_pokemon_id', 'request_pokemon');
        });
    }
};
