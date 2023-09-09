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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('daily_gift')->delete(1);
        DB::table('daily_gift')->delete(2);
        DB::table('daily_gift')->delete(3);
        DB::table('daily_gift')->delete(4);
        DB::table('daily_gift')->delete(5);
        DB::table('daily_gift')->delete(6);
        DB::table('daily_gift')->delete(7);
        DB::table('daily_gift')->delete(8);
        DB::table('daily_gift')->delete(9);
        DB::table('daily_gift')->delete(10);
        DB::table('daily_gift')->delete(11);
        DB::table('daily_gift')->delete(12);
        DB::table('daily_gift')->delete(13);
        DB::table('daily_gift')->delete(14);
        DB::table('daily_gift')->delete(15);
        DB::table('daily_gift')->delete(16);
        DB::table('daily_gift')->delete(17);
        DB::table('daily_gift')->delete(18);
    }
};
