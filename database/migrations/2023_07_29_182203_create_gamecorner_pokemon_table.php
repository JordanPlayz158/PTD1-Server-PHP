<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gamecorner_pokemon', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('pNum', false, true);
            $table->string('nickname', 255);
            $table->mediumInteger('exp', false, true);
            $table->tinyInteger('lvl', false, true);
            $table->smallInteger('m1', false, true);
            $table->smallInteger('m2', false, true);
            $table->smallInteger('m3', false, true);
            $table->smallInteger('m4', false, true);
            $table->smallInteger('ability', false, true);
            $table->tinyInteger('mSel', false, true);
            $table->tinyInteger('targetType', false, true);
            $table->tinyInteger('shiny', false, true);  
            $table->integer('cost', false, true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gamecorner_pokemon');
    }
};
