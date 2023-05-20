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
            $table->dropPrimary();
        });

        Schema::table('achievements', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->first();
            $table->unsignedBigInteger('user_id')->after('id');
        });

        foreach(DB::table('achievements')->select('id', 'email')->lazyById() as $achievement) {
            $email = $achievement->email;

            $user = DB::table('users')
                ->select('id')
                ->where('email', '=', $email)
                ->limit(1)
                ->first();

            DB::table('achievements')->where('email', '=', $email)->update(['user_id' => $user->id]);
        }

        Schema::table('achievements', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(!in_array('email', Schema::getColumnListing('achievements'))) {
            Schema::table('achievements', function (Blueprint $table) {
                $table->string('email', 50);
            });
        }

        foreach(DB::table('achievements')->select('id', 'user_id')->lazyById() as $achievement) {
            $user_id = $achievement->user_id;

            $email = DB::table('users')
                ->select('email')
                ->where('id', '=', $user_id)
                ->limit(1)
                ->first()->email;

            DB::table('achievements')->where('user_id', '=', $user_id)->update(['email' => $email]);
        }


        Schema::table('achievements', function (Blueprint $table) {
            $table->dropPrimary();
        });

        Schema::dropColumns('achievements', ['id', 'user_id']);

        Schema::table('achievements', function (Blueprint $table) {
            $table->primary('email');
        });
    }
};
