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
        // BUTTON 1 -------------------------------
        DB::table('daily_gift')->insert([
            'button' => 1,
            'prize' => 1000,
            'cost' => 1000,
            'percentage' => 0.50
        ]);

        DB::table('daily_gift')->insert([
            'button' => 1,
            'prize' => 10000,
            'cost' => 1000,
            'percentage' => 0.49934
        ]);

        DB::table('daily_gift')->insert([
            'button' => 1,
            'prize' => 1,
            'cost' => 1000,
            'percentage' => 0.0005
        ]);

        DB::table('daily_gift')->insert([
            'button' => 1,
            'prize' => 5,
            'cost' => 1000,
            'percentage' => 0.0001
        ]);

        DB::table('daily_gift')->insert([
            'button' => 1,
            'prize' => 10,
            'cost' => 1000,
            'percentage' => 0.00005
        ]);

        DB::table('daily_gift')->insert([
            'button' => 1,
            'prize' => 20,
            'cost' => 1000,
            'percentage' => 0.00001
        ]);

        // BUTTON 2 -------------------------------
        DB::table('daily_gift')->insert([
            'button' => 2,
            'prize' => 10000,
            'cost' => 10000,
            'percentage' => 0.50
        ]);

        DB::table('daily_gift')->insert([
            'button' => 2,
            'prize' => 25000,
            'cost' => 10000,
            'percentage' => 0.49835
        ]);

        DB::table('daily_gift')->insert([
            'button' => 2,
            'prize' => 1,
            'cost' => 1000,
            'percentage' => 0.001
        ]);

        DB::table('daily_gift')->insert([
            'button' => 2,
            'prize' => 5,
            'cost' => 1000,
            'percentage' => 0.0005
        ]);

        DB::table('daily_gift')->insert([
            'button' => 2,
            'prize' => 10,
            'cost' => 1000,
            'percentage' => 0.0001
        ]);

        DB::table('daily_gift')->insert([
            'button' => 2,
            'prize' => 20,
            'cost' => 1000,
            'percentage' => 0.00005
        ]);

        // BUTTON 3 -------------------------------
        DB::table('daily_gift')->insert([
            'button' => 3,
            'prize' => 50000,
            'cost' => 100000,
            'percentage' => 0.50
        ]);

        DB::table('daily_gift')->insert([
            'button' => 3,
            'prize' => 100000,
            'cost' => 100000,
            'percentage' => 0.4934
        ]);

        DB::table('daily_gift')->insert([
            'button' => 3,
            'prize' => 1,
            'cost' => 1000,
            'percentage' => 0.005
        ]);

        DB::table('daily_gift')->insert([
            'button' => 3,
            'prize' => 5,
            'cost' => 1000,
            'percentage' => 0.001
        ]);

        DB::table('daily_gift')->insert([
            'button' => 3,
            'prize' => 10,
            'cost' => 1000,
            'percentage' => 0.0005
        ]);

        DB::table('daily_gift')->insert([
            'button' => 3,
            'prize' => 20,
            'cost' => 1000,
            'percentage' => 0.0001
        ]);
    }
}
