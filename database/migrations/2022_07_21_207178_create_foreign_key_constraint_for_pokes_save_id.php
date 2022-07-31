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
            $table->index('save_id', 'save_id');
            $table->foreign('save_id', 'FK_saves_pokes')->references('id')->on('saves')->cascadeOnDelete();
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
            $table->dropIndex('save_id');
            $table->dropForeign('FK_saves_pokes');
        });
    }
};
