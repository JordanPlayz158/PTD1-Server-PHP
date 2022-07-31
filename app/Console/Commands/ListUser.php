<?php

namespace App\Console\Commands;

use App\Models\Poke;
use App\Models\Save;
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
        $users = DB::table('users')->where('users.email', $email)
            ->join('saves', 'users.id', '=', 'saves.user_id')
            ->join('pokes', 'saves.id', '=', 'pokes.save_id')
            ->join('trades', 'users.email', '=', 'trades.email')
            ->join('achievements', 'users.email', '=', 'achievements.email')
            //->join('offers', 'offers.offerSave', '=', 'saves.id')
            //->join('offers', 'offers.requestSave', '=', 'saves.id')
            ->get()->all();

        if(sizeof($users) === 0) {
            $users = [];

            $users = array_merge($users, DB::table('pokes')->where('email', $email)->get()->all());
            $users = array_merge($users, DB::table('trades')->where('email', $email)->get()->all());
            $users = array_merge($users, DB::table('achievements')->where('email', $email)->get()->all());
        }

        $this->info(print_r($users, true));
        return 0;
    }
}
