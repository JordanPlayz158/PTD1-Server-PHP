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
        if(!in_array('poke_id', Schema::getColumnListing('trades'))) {
            Schema::table('trades', function (Blueprint $table) {
                $table->unsignedBigInteger('poke_id');
            });
        }

        if(!in_array('autoincrement_id', Schema::getColumnListing('trades'))) {
            // To remind me in the future, I separated them rather than combined them
            // because laravel has a tendency to (assumption) combine the sql or not
            // run the sql in the order specified which can lead to some issues
            // like the auto_increment column not being able to be made
            // after the primary key is dropped in the same change
            // by doing it like this, I ensure they do the changes
            // in the exact order I specify
            Schema::table('trades', function (Blueprint $table) {
                $table->dropPrimary();
            });

            Schema::table('trades', function (Blueprint $table) {
                $table->unique(['email', 'num', 'id']);
            });

            Schema::table('trades', function (Blueprint $table) {
                $table->unsignedBigInteger('autoincrement_id', true);
            });
        }

        $rowNum = DB::table('trades')->count('id');
        $rowUpdateCounter = 0;

        $output = new ConsoleOutput();
        $output->writeln('');
        $progress = new ProgressBar($output, $rowNum);
        $progress->start();

        foreach (DB::table('trades')->lazyById(1000, 'autoincrement_id') as $trade) {
            $user = DB::table('users')
                ->where('email', '=', $trade->email)
                ->select('id')
                ->limit(1)
                ->first();

            $save = DB::table('saves')
                ->where('user_id', '=', $user->id)
                ->where('num', '=', $trade->num)
                ->select('id')
                ->limit(1)
                ->first();

            $poke = DB::table('pokes')
                ->where('save_id', '=', $save->id)
                ->where('pId', '=', $trade->id)
                ->select('id')
                ->limit(1)
                ->first();

            $tradeEntry = DB::table('trades')
                ->where('email', '=', $trade->email)
                ->where('num', '=', $trade->num)
                ->where('id', '=', $trade->id)
                ->limit(1);

            if ($poke === null) {
                Log::info("Trade Entry is being deleted due to pokemon corresponding to trade not being found.", [$tradeEntry]);
                $tradeEntry->delete();
                continue;
            }

            $trade->poke_id = $poke->id;

            if ($trade->poke_id === 0) {
                Log::info("Trade Entry skipped being updated due to the pokemon's id being 0", [$trade, $poke]);
                continue;
            }

            $tradeEntry->update(['poke_id' => $trade->poke_id]);

            $rowUpdateCounter++;

            $progress->advance();
        }

        $progress->finish();

        Log::info($rowUpdateCounter . ' trades updated with poke_id from ' . $rowNum . ' trades');

        Schema::dropColumns('trades', ['email', 'num', 'id', 'autoincrement_id']);

        Schema::table('trades', function (Blueprint $table) {
            $table->primary('poke_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trades', function (Blueprint $table) {
            $table->dropPrimary();
        });

        Schema::table('trades', function (Blueprint $table) {
            $table->mediumInteger('poke_localId', false, true)->nullable(false)->first();
            $table->tinyInteger('num', false, true)->nullable(false)->first();
            $table->string('email', 50)->nullable(false)->first();

            $table->primary(['email', 'num', 'poke_localId']);
        });

        $rowNum = DB::table('trades')->count('poke_id');
        $rowUpdateCounter = 0;

        $output = new ConsoleOutput();
        $output->writeln('');
        $progress = new ProgressBar($output, $rowNum);
        $progress->start();

        foreach (DB::table('trades')->lazyById() as $trade) {
            $poke = DB::table('pokes')
                ->where('id', '=', $trade->id)
                ->select(['save_id', 'id'])
                ->limit(1)
                ->first();

            $save = DB::table('saves')
                ->where('id', '=', $poke->save_id)
                ->select(['user_id', 'num'])
                ->limit(1)
                ->first();

            $user = DB::table('users')
                ->where('id', '=', $save->user_id)
                ->select('email')
                ->limit(1)
                ->first();

            // Change poke_id to id in where clause
            DB::table('trades')
                ->where('poke_id')
                ->update(['email' => $user->email, 'num' => $save->num, 'poke_localId' => $poke->id]);

            $rowUpdateCounter++;
            $progress->advance();
        }

        $progress->finish();
        Log::info($rowUpdateCounter . ' trades updated with email, num, and poke_localId from ' . $rowNum . ' trades');

        // Change poke_id to id
        Schema::dropColumns('trades', 'poke_id');
        Schema::table('trades', function(Blueprint $table) {
            $table->renameColumn('poke_localId', 'id');
        });

        Schema::dropColumns('trades', ['poke_id', 'autoincrement_id']);

        Schema::table('trades', function (Blueprint $table) {
            $table->dropUnique('trades_email_num_id_unique');
        });

        Schema::table('trades', function (Blueprint $table) {
            $table->primary(['email', 'num', 'poke_id']);
        });

    }
};
