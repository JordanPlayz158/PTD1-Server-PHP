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
        Schema::table('achievements', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('pokes', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('saves', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('trades', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('achievements', ['created_at', 'updated_at']);
        Schema::dropColumns('offers', ['created_at', 'updated_at']);
        Schema::dropColumns('pokes', ['created_at', 'updated_at']);
        Schema::dropColumns('saves', ['created_at', 'updated_at']);
        Schema::dropColumns('trades', ['created_at', 'updated_at']);

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable(true)->default(null)->change();
        });
    }
};
