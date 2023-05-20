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
        Schema::table('pokemon', function (Blueprint $table) {
            $table->mediumInteger('pNum')->unsigned()->nullable(false)->change();
            $table->string('nickname')->nullable(false)->change();
            $table->mediumInteger('exp')->unsigned()->nullable(false)->change();
            $table->unsignedTinyInteger('lvl')->nullable(false)->change();
            $table->unsignedTinyInteger('mSel')->nullable(false)->change();
            $table->mediumInteger('pos')->unsigned()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pokemon', function (Blueprint $table) {
            $table->mediumInteger('pNum')->unsigned()->nullable(true)->change();
            $table->string('nickname')->nullable(true)->change();
            $table->mediumInteger('exp')->unsigned()->nullable(true)->change();
            $table->unsignedTinyInteger('lvl')->nullable(true)->change();
            $table->unsignedTinyInteger('mSel')->nullable(true)->change();
            $table->mediumInteger('pos')->unsigned()->nullable(true)->change();
        });
    }
};
