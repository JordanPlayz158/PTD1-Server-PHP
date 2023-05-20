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
            $table->integer('pNum')->unsigned()->nullable(false)->change();
            $table->string('nickname')->nullable(false)->change();
            $table->integer('exp')->unsigned()->nullable(false)->change();
            $table->unsignedSmallInteger('lvl')->nullable(false)->change();
            $table->unsignedSmallInteger('mSel')->nullable(false)->change();
            $table->integer('pos')->unsigned()->nullable(false)->change();
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
            $table->integer('pNum')->unsigned()->nullable(true)->change();
            $table->string('nickname')->nullable(true)->change();
            $table->integer('exp')->unsigned()->nullable(true)->change();
            $table->unsignedSmallInteger('lvl')->nullable(true)->change();
            $table->unsignedSmallInteger('mSel')->nullable(true)->change();
            $table->integer('pos')->unsigned()->nullable(true)->change();
        });
    }
};
