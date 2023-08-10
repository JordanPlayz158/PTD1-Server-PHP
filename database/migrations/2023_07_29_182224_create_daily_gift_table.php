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
        Schema::create('daily_gift', function (Blueprint $table) {
            $table->id();
            $table->enum('button', [1, 2 ,3]);
            $table->integer('prize', false, true);
            $table->integer('cost', false, true);
            $table->decimal('percentage', 5, 5, true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_gift');
    }
};
