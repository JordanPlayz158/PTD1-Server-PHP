<?php

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
        if(!Schema::hasColumn('pokes', 'save_id')) {
            Schema::table('pokes', function (Blueprint $table) {
                $table->bigInteger('save_id', false, true)->after('id');
            });
        }

        $rowNum = DB::table('pokes')->count('id');
        $rowUpdateCounter = 0;

        $output = new ConsoleOutput();
        $output->writeln('');
        $progress = new ProgressBar($output, $rowNum);
        $progress->start();

        foreach (DB::table('pokes')->select(['save_id', 'email', 'id', 'num'])->lazyById() as $poke) {
            if ($poke->save_id !== 0) {
                Log::info("Pokemon's Save ID is already set, no need to reassign it.", [$poke->save_id]);
                continue;
            }

            $user = DB::table('users')
                ->where('email', '=', $poke->email)
                ->select('id')->limit(1)->first();

            if ($user === null) {
                Log::info('User not found with email "' . $poke->email . '". Deleting', [$poke]);

                // If the user is not found, we know this pokes record is invalid, so we will delete it
                DB::table('pokes')->where('id', '=', $poke->id)->delete();
                continue;
            }

            $save = DB::table('saves')
                ->where('user_id', '=', $user->id)
                ->where('num', '=', $poke->num)
                ->select('id')->limit(1)->first();
            DB::table('pokes')->where('id', '=', $poke->id)->update(['save_id' => $save->id]);
            $rowUpdateCounter++;

            $progress->advance();
        }

        $progress->finish();

        Log::info($rowUpdateCounter . ' pokes updated with save_id from ' . $rowNum . ' pokes');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $rowNum = DB::table('pokes')->count('save_id');
        $rowUpdateCounter = 0;

        $output = new ConsoleOutput();
        $output->writeln('');
        $progress = new ProgressBar($output, $rowNum);
        $progress->start();

        foreach (DB::table('pokes')->select(['save_id', 'id'])->lazyById() as $poke) {
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

            $rowUpdateCounter++;
            $progress->advance();
        }

        $progress->finish();

        Log::info($rowUpdateCounter . ' pokes updated with save_id from ' . $rowNum . ' pokes');

        Schema::dropColumns('pokes', 'user_id');
    }
};
