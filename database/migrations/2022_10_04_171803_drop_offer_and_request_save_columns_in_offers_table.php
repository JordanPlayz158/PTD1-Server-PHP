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
            $table->dropForeign('FK_offerSave_offers');
            $table->dropColumn('offerSave');

            $table->dropForeign('FK_requestSave_offers');
            $table->dropColumn('requestSave');
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
            $table->unsignedBigInteger('offerSave');
            $table->unsignedBigInteger('requestSave');
        });
    }
};
