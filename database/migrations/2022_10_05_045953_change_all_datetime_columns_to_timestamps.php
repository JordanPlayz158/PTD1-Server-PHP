<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

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
            $table->timestamp('created_at')->nullable(true)->change();
            $table->timestamp('updated_at')->nullable(true)->change();
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable(true)->change();
            $table->timestamp('updated_at')->nullable(true);
        });

        Schema::table('pokemon', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable(true)->change();
            $table->timestamp('updated_at')->nullable(true)->change();
        });

        Schema::table('saves', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable(true)->change();
            $table->timestamp('updated_at')->nullable(true)->change();
        });

        Schema::table('trades', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable(true)->change();
            $table->timestamp('updated_at')->nullable(true);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable(true)->change();
            $table->timestamp('updated_at')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Opted against reversing this one as datetime that is relative to the server
        // doesn't seem like a good idea and wasn't my original intent when using that column
    }
};
