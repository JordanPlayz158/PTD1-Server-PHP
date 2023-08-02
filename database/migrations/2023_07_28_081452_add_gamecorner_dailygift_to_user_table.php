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
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('casino_coins', false, true)->default(0)->after('ptd_coins');
            $table->timestamp('last_used_dg')->nullable(true)->after('casino_coins');
            $table->timestamp('last_used_gc')->nullable(true)->after('last_used_dg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('users', ['casino_coins', 'last_used_dg', 'last_used_gc']);
    }
};
