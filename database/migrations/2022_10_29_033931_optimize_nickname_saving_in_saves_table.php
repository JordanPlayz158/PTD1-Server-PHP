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

        foreach(DB::table('saves')->select(['id', 'nickname'])->lazyById() as $save) {
            if($save->nickname == 'Satoshi') {
                DB::table('saves')->where('id', '=', $save->id)
                    ->update(['nickname' => null]);
                $rowInsertCounter++;
            }

            $progress->advance();
        }

        $progress->finish();
        Log::info($rowInsertCounter . ' names updated from ' . $rowNum . ' saves');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach(DB::table('saves')->select(['id', 'nickname'])->lazyById() as $save) {
            if($save->nickname == null) {
                DB::table('saves')->where('id', '=', $save->id)
                    ->update(['nickname' => 'Satoshi']);
            }
        }
    }
};
