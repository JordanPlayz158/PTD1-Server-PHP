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
        if(!Schema::hasColumn('saves', 'user_id')) {
            Schema::table('saves', function (Blueprint $table) {
                $table->bigInteger('user_id', false, true)->after('id');
            });
        }

        $rowNum = DB::table('saves')->count('email');
        $rowUpdateCounter = 0;

        $output = new ConsoleOutput();
        $output->writeln('');
        $progress = new ProgressBar($output, $rowNum);
        $progress->start();

        foreach (DB::table('saves')->select(['user_id', 'email', 'id'])->lazyById() as $save) {
            if ($save->user_id !== 0) {
                Log::info("Save's User ID is already set, no need to reassign it.", [$save->user_id]);
                continue;
            }

            $user = DB::table('users')->where('email', '=', $save->email)->select('id')->limit(1)->first();
            DB::table('saves')->where('id', '=', $save->id)->update(['user_id' => $user->id]);
            $rowUpdateCounter++;
            $progress->advance();
        }

        $progress->finish();

        Log::info($rowUpdateCounter . ' saves updated with user_id from ' . $rowNum . ' saves');

        Schema::table('saves', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('saves', function (Blueprint $table) {
            $table->dropPrimary();
            $table->primary('id');
            $table->dropUnique('uuid');
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

        $rowNum = DB::table('saves')->count('user_id');
        $rowUpdateCounter = 0;

        $output = new ConsoleOutput();
        $output->writeln('');
        $progress = new ProgressBar($output, $rowNum);
        $progress->start();

        foreach (DB::table('saves')->select(['user_id', 'id'])->lazyById() as $save) {
            $user = DB::table('users')->where('id', '=', $save->user_id)->select('email')->limit(1)->first();
            DB::table('saves')->where('id', '=', $save->id)->update(['email' => $user->email]);
            $rowUpdateCounter++;
            $progress->advance();
        }

        $progress->finish();

        Log::info($rowUpdateCounter . ' saves updated with email from ' . $rowNum . ' saves');

        Schema::table('saves', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');

            $table->dropPrimary();
            $table->primary(['email', 'num']);
            $table->dropUnique('saves_user_id_num_unique');
            $table->unique('id', 'saves_uuid_unique');
        });
    }
};
