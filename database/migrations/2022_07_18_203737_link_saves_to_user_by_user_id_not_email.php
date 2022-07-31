<?php

use App\Models\User;
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
        if(!Schema::hasColumn('saves', 'user_id')) {
            Schema::table('saves', function (Blueprint $table) {
                $table->bigInteger('user_id', false, true)->after('id');
            });
        }

        DB::table('saves')->chunkById(100, function ($saves) {
            foreach ($saves as $save) {
                if($save->user_id !== 0) {
                    continue;
                }

                $user = DB::table('users')->where('email', '=', $save->email)->select('id')->limit(1)->first();
                DB::table('saves')->where('id', '=', $save->id)->update(['user_id' => $user->id]);
            }
        });

        Schema::table('saves', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('saves', function (Blueprint $table) {
            $table->dropPrimary();
            $table->primary('id');
            $table->dropUnique('saves_uuid_unique');
            $table->unique(['user_id', 'num']);
        });

        Schema::dropColumns('saves', 'email');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saves', function (Blueprint $table) {
            $table->string('email');
        });

        DB::table('saves')->chunkById(100, function ($saves) {
            foreach ($saves as $save) {
                $user = DB::table('users')->where('id', '=', $save->user_id)->select('email')->limit(1)->first();
                DB::table('saves')->where('id', '=', $save->id)->update(['email' => $user->email]);
            }
        });

        Schema::table('saves', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');

            $table->dropPrimary();
            $table->primary(['email', 'num']);
            $table->dropUnique('saves_user_id_num_unique');
            $table->unique('id', 'saves_uuid_unique');
        });
    }
};
