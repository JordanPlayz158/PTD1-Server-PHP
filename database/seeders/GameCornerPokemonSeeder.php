<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameCornerPokemonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gamecorner_pokemon')->insert([
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

        DB::table('gamecorner_pokemon')->insert([
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

        DB::table('gamecorner_pokemon')->insert([
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

        DB::table('gamecorner_pokemon')->insert([
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

        DB::table('gamecorner_pokemon')->insert([
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

        DB::table('gamecorner_pokemon')->insert([
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

        DB::table('gamecorner_pokemon')->insert([
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


        DB::table('gamecorner_pokemon')->insert([
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

        DB::table('gamecorner_pokemon')->insert([
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

        DB::table('gamecorner_pokemon')->insert([
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

        DB::table('gamecorner_pokemon')->insert([
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

        DB::table('gamecorner_pokemon')->insert([
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

        DB::table('gamecorner_pokemon')->insert([
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
}
