<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DailyGiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('daily_gift')->insert([
            'id' => 1,
            'prize' => 1000,
            'cost' => 1000
        ]);

        DB::table('daily_gift')->insert([
            'id' => 2,
            'prize' => 10000,
            'cost' => 1000
        ]);

        DB::table('daily_gift')->insert([
            'id' => 3,
            'prize' => 10000,
            'cost' => 10000
        ]);

        DB::table('daily_gift')->insert([
            'id' => 4,
            'prize' => 25000,
            'cost' => 10000
        ]);

        DB::table('daily_gift')->insert([
            'id' => 5,
            'prize' => 50000,
            'cost' => 100000
        ]);

        DB::table('daily_gift')->insert([
            'id' => 6,
            'prize' => 100000,
            'cost' => 100000
        ]);
    }
}
