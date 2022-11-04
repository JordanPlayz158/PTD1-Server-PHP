<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

return new class extends Migration
{
    /* Users columns       - Accounts columns
     *   id                - N/A
     *   name              - accNickname
     *   email             - email
     *   email_verified_at - N/A
     *   password          - pass
     *   dex               - dex1
     *   shinyDex          - dex1Shiny
     *   shadowDex         - dex1Shadow
     *   remember_token    - N/A
     *   created_at        - N/A
     *   updated_at        - N/A
     */

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('dex', 151)->nullable(true)->after('password');
            $table->string('shinyDex', 151)->nullable(true)->after('dex');
            $table->string('shadowDex', 151)->nullable(true)->after('shinyDex');
        });

        $rowNum = DB::table('accounts')->count('email');
        $rowInsertCounter = 0;

        $output = new ConsoleOutput();
        $output->writeln('');
        $progress = new ProgressBar($output, $rowNum);
        $progress->start();

        foreach (DB::table('accounts')->lazyById(1000, 'email') as $account) {
            $email = $account->email;
            $name = $account->accNickname;

            DB::table('users')->insert([
                'name' => $name == null ? $email : $name,
                'email' => $email,
                'password' => $account->pass,
                'dex' => $account->dex1,
                'shinyDex' => $account->dex1Shiny,
                'shadowDex' => $account->dex1Shadow,
                'created_at' => Carbon::now()
            ]);

            $rowInsertCounter++;
            $progress->advance();
        }

        $progress->finish();

        Log::info($rowInsertCounter . ' users inserted from ' . $rowNum . ' accounts');

        Schema::dropIfExists('accounts');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::create('accounts', function (Blueprint $table) {
                $table->string('email', 50)->nullable(false)->primary();
                $table->string('pass', 255)->nullable(false);
                $table->string('accNickname', 255);
                $table->string('dex1', 151);
                $table->string('dex1Shiny', 151);
                $table->string('dex1Shadow', 151);
            });

            $rowNum = DB::table('users')->count('email');
            $rowInsertCounter = 0;

            $output = new ConsoleOutput();
            $output->writeln('');
            $progress = new ProgressBar($output, $rowNum);
            $progress->start();

            foreach (DB::table('users')->select(['email', 'password', 'name', 'dex', 'shinyDex', 'shadowDex'])->lazyById() as $user) {
                DB::table('accounts')->insert([
                    'email' => $user->email,
                    'pass' => $user->password,
                    'accNickname' => $user->name,
                    'dex1' => $user->dex,
                    'dex1Shiny' => $user->shinyDex,
                    'dex1Shadow' => $user->shadowDex
                ]);

                $rowInsertCounter++;
                $progress->advance();
            }

            $progress->finish();

            Log::info($rowInsertCounter . ' accounts inserted from ' . $rowNum . ' users');

            DB::table('users')->truncate();

            $table->dropColumn(['dex', 'shinyDex', 'shadowDex']);
        });
    }
};
