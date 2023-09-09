<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('game_corner_pokemon')->insert([
            'pNum' => 0,
            'nickname' => 'Random Shadow Pokemon',
            'exp' => 0,
            'lvl' => 1,
            'm1' => 368,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'ability' => 0,
            'mSel' => 1,
            'targetType' => 1,
            'shiny' => 2,
            'cost' => 300000,
        ]);

        DB::table('game_corner_pokemon')->insert([
            'pNum' => 63,
            'nickname' => 'Abra',
            'exp' => 0,
            'lvl' => 1,
            'm1' => 116,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'ability' => 0,
            'mSel' => 1,
            'targetType' => 1,
            'shiny' => 0,
            'cost' => 120,
        ]);

        DB::table('game_corner_pokemon')->insert([
            'pNum' => 35,
            'nickname' => 'Clefairy',
            'exp' => 0,
            'lvl' => 1,
            'm1' => 48,
            'm2' => 5,
            'm3' => 0,
            'm4' => 0,
            'ability' => 0,
            'mSel' => 1,
            'targetType' => 1,
            'shiny' => 0,
            'cost' => 500,
        ]);

        DB::table('game_corner_pokemon')->insert([
            'pNum' => 127,
            'nickname' => 'Pinsir',
            'exp' => 0,
            'lvl' => 1,
            'm1' => 269,
            'm2' => 12,
            'm3' => 0,
            'm4' => 0,
            'ability' => 0,
            'mSel' => 1,
            'targetType' => 1,
            'shiny' => 0,
            'cost' => 2500,
        ]);

        DB::table('game_corner_pokemon')->insert([
            'pNum' => 147,
            'nickname' => 'Dratini',
            'exp' => 0,
            'lvl' => 1,
            'm1' => 70,
            'm2' => 43,
            'm3' => 0,
            'm4' => 0,
            'ability' => 0,
            'mSel' => 1,
            'targetType' => 1,
            'shiny' => 0,
            'cost' => 2800,
        ]);

        DB::table('game_corner_pokemon')->insert([
            'pNum' => 123,
            'nickname' => 'Scyther',
            'exp' => 0,
            'lvl' => 1,
            'm1' => 4,
            'm2' => 43,
            'm3' => 0,
            'm4' => 0,
            'ability' => 0,
            'mSel' => 1,
            'targetType' => 1,
            'shiny' => 0,
            'cost' => 5500,
        ]);

        DB::table('game_corner_pokemon')->insert([
            'pNum' => 137,
            'nickname' => 'Porygon',
            'exp' => 0,
            'lvl' => 1,
            'm1' => 1,
            'm2' => 263,
            'm3' => 0,
            'm4' => 0,
            'ability' => 0,
            'mSel' => 1,
            'targetType' => 1,
            'shiny' => 0,
            'cost' => 6500,
        ]);


        DB::table('game_corner_pokemon')->insert([
            'pNum' => 63,
            'nickname' => 'Abra',
            'exp' => 0,
            'lvl' => 1,
            'm1' => 116,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'ability' => 0,
            'mSel' => 1,
            'targetType' => 1,
            'shiny' => 1,
            'cost' => 9000,
        ]);

        DB::table('game_corner_pokemon')->insert([
            'pNum' => 35,
            'nickname' => 'Clefairy',
            'exp' => 0,
            'lvl' => 1,
            'm1' => 48,
            'm2' => 5,
            'm3' => 0,
            'm4' => 0,
            'ability' => 0,
            'mSel' => 1,
            'targetType' => 1,
            'shiny' => 1,
            'cost' => 15000,
        ]);

        DB::table('game_corner_pokemon')->insert([
            'pNum' => 127,
            'nickname' => 'Pinsir',
            'exp' => 0,
            'lvl' => 1,
            'm1' => 269,
            'm2' => 12,
            'm3' => 0,
            'm4' => 0,
            'ability' => 0,
            'mSel' => 1,
            'targetType' => 1,
            'shiny' => 1,
            'cost' => 50000,
        ]);

        DB::table('game_corner_pokemon')->insert([
            'pNum' => 147,
            'nickname' => 'Dratini',
            'exp' => 0,
            'lvl' => 1,
            'm1' => 70,
            'm2' => 43,
            'm3' => 0,
            'm4' => 0,
            'ability' => 0,
            'mSel' => 1,
            'targetType' => 1,
            'shiny' => 1,
            'cost' => 100000,
        ]);

        DB::table('game_corner_pokemon')->insert([
            'pNum' => 123,
            'nickname' => 'Scyther',
            'exp' => 0,
            'lvl' => 1,
            'm1' => 4,
            'm2' => 43,
            'm3' => 0,
            'm4' => 0,
            'ability' => 0,
            'mSel' => 1,
            'targetType' => 1,
            'shiny' => 1,
            'cost' => 120000,
        ]);

        DB::table('game_corner_pokemon')->insert([
            'pNum' => 137,
            'nickname' => 'Porygon',
            'exp' => 0,
            'lvl' => 1,
            'm1' => 1,
            'm2' => 263,
            'm3' => 0,
            'm4' => 0,
            'ability' => 0,
            'mSel' => 1,
            'targetType' => 1,
            'shiny' => 1,
            'cost' => 150000,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('game_corner_pokemon')->delete(1);
        DB::table('game_corner_pokemon')->delete(2);
        DB::table('game_corner_pokemon')->delete(3);
        DB::table('game_corner_pokemon')->delete(4);
        DB::table('game_corner_pokemon')->delete(5);
        DB::table('game_corner_pokemon')->delete(6);
        DB::table('game_corner_pokemon')->delete(7);
        DB::table('game_corner_pokemon')->delete(8);
        DB::table('game_corner_pokemon')->delete(9);
        DB::table('game_corner_pokemon')->delete(10);
        DB::table('game_corner_pokemon')->delete(11);
        DB::table('game_corner_pokemon')->delete(12);
        DB::table('game_corner_pokemon')->delete(13);
    }
};
