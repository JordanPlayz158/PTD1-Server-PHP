<?php

use App\Models\User;
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
        $rowNum = DB::table('users')->count('id');
        $rowInsertCounter = 0;

        $output = new ConsoleOutput();
        $output->writeln('');
        $progress = new ProgressBar($output, $rowNum);
        $progress->start();

        foreach(DB::table('users')->lazyById() as $user) {
            $dex = rtrim($user->dex, '0');
            $shinyDex = rtrim($user->shinyDex, '0');
            $shadowDex = rtrim($user->shadowDex, '0');

            $dex = empty($dex) ? null : $dex;
            $shinyDex = empty($shinyDex) ? null : $shinyDex;
            $shadowDex = empty($shadowDex) ? null : $shadowDex;

            DB::table('users')->where('id', '=', $user->id)
                ->update(['dex' => $dex, 'shinyDex' => $shinyDex, 'shadowDex' => $shadowDex]);

            $rowInsertCounter++;
            $progress->advance();
        }

        $progress->finish();
        Log::info($rowInsertCounter . ' dex\'s updated from ' . $rowNum . ' users');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach(DB::table('users')->lazyById() as $user) {
            $dex = $this->fillDex($user->dex);
            $shinyDex = $this->fillDex($user->shinyDex);
            $shadowDex = $this->fillDex($user->shadowDex);

            DB::table('users')->where('id', '=', $user->id)
                ->update(['dex' => $dex, 'shinyDex' => $shinyDex, 'shadowDex' => $shadowDex]);
        }
    }

    private function fillDex(string|null $dex): string
    {
        if($dex === null) {
            $dex = '0';
        }

        while (strlen($dex) < 151)
            $dex .= '0';

        return $dex;
    }
};
