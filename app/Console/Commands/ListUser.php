<?php

namespace App\Console\Commands;

use App\Models\User;
use DB;
use Illuminate\Console\Command;

class ListUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:view {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Views a user by email';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');

        $user = User::whereEmail($email)->limit(1);

        if($user->count() < 1) {
            $user = ['email' => $email];

            $user['pokes'] = DB::table('pokes')->where('email', $email)->get()->all();
            $user['trades'] = DB::table('trades')->where('email', $email)->get()->all();
            $user['achievements'] = DB::table('achievements')->where('email', $email)->get()->all();

            $this->info(json_encode($user));
            return 0;
        }

        $user = $user->with(['saves', 'saves.pokes', 'achievement'])->first();

        //$offers[] = DB::table('offers')->where('offerSave', $saveId)->orWhere('requestSave', $saveId)->get()->all();

        $this->info(json_encode($user));
        return 0;
    }
}
