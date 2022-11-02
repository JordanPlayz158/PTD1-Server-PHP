<?php

use Illuminate\Database\Migrations\Migration;
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
        $rowNum = DB::table('users')->count('id');
        $rowInsertCounter = 0;

        $output = new ConsoleOutput();
        $output->writeln('');
        $progress = new ProgressBar($output, $rowNum);
        $progress->start();

        foreach(DB::table('users')->select(['id', 'name', 'email'])->lazyById() as $user) {
            if($user->name == $user->email) {
                DB::table('users')->where('id', '=', $user->id)
                    ->update(['name' => null]);
                $rowInsertCounter++;
            }

            $progress->advance();
        }

        $progress->finish();
        Log::info($rowInsertCounter . ' names updated from ' . $rowNum . ' users');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach(DB::table('users')->select(['id', 'name', 'email'])->lazyById() as $user) {
            if($user->name == null) {
                DB::table('users')->where('id', '=', $user->id)
                    ->update(['name' => $user->email]);
            }
        }
    }
};
