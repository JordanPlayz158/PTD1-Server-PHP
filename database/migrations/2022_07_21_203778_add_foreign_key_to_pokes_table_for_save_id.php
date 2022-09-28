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
        Schema::table('pokes', function (Blueprint $table) {
            $table->foreign('save_id')->references('id')->on('saves');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pokes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('save_id');
        });
    }
};
