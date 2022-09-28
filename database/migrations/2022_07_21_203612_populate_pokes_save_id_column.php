<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
//use Symfony\Component\Console\Output\ConsoleOutput;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('pokes', 'save_id')) {
            Schema::table('pokes', function (Blueprint $table) {
                $table->bigInteger('save_id', false, true)->after('id');
            });
        }

        //$out = new ConsoleOutput();
        DB::table('pokes')->chunkById(100, function ($pokes) /*use ($out)*/ {
            foreach ($pokes as $poke) {
                //$out->writeln($poke->id);

                if($poke->save_id !== 0) {
                    continue;
                }

                $user = DB::table('users')
                    ->where('email', '=', $poke->email)
                    ->select('id')->limit(1)->first();

                if($user === null) {
                    $message = 'User not found with email "' . $poke->email . '"';
                    Log::info($message);

                    //$out->writeln($message);

                    // If the user is not found, we know this pokes record is invalid, so we will delete it
                    DB::table('pokes')->where('id', '=', $poke->id)->delete();
                    continue;
                }

                $save = DB::table('saves')
                    ->where('user_id', '=', $user->id)
                    ->where('num', '=', $poke->num)
                    ->select('id')->limit(1)->first();
                DB::table('pokes')->where('id', '=', $poke->id)->update(['save_id' => $save->id]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('pokes')->chunkById(100, function ($pokes) {
            foreach ($pokes as $poke) {
                $save = DB::table('saves')
                    ->where('id', $poke->save_id)
                    ->select('user_id', 'num')->limit(1)->first();

                $user = DB::table('users')
                    ->where('id', $save->user_id)
                    ->select('email')->limit(1)->first();

                DB::table('pokes')->where('id', $poke->id)->update([
                    'email' => $user->email,
                    'num' => $save->num
                ]);
            }
        });

        Schema::dropColumns('pokes', 'user_id');
    }
};
