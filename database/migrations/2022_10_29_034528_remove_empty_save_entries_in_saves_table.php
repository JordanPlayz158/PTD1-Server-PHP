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
        $rowNum = DB::table('saves')->count('id');
        $rowInsertCounter = 0;

        $output = new ConsoleOutput();
        $output->writeln('');
        $progress = new ProgressBar($output, $rowNum);
        $progress->start();

        foreach(DB::table('saves')->select(['id'])->lazyById() as $save) {
            if(DB::table('pokemon')->where('save_id', '=', $save->id)->count() == 0) {
                DB::table('saves')->where('id', '=', $save->id)->delete();
                $rowInsertCounter++;
            }

            $progress->advance();
        }

        $progress->finish();
        Log::info($rowInsertCounter . ' saves dropped from ' . $rowNum . ' saves');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Possible to reverse but not worth it
    }
};
