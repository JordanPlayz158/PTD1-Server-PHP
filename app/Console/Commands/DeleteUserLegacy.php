<?php

namespace App\Console\Commands;

use App\Models\Pokemon;
use App\Models\Save;
use DB;
use Illuminate\Console\Command;

class DeleteUserLegacy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deleteUserLegacy:delete {email} {checkUserExists=true}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a user by manually removing them from all tables on legacy layouts where it may be impeding migration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        $users = DB::table('users')->where('email', $email)->select('id', 'email')->limit(1);

        $userExists = filter_var($this->argument('checkUserExists'), FILTER_VALIDATE_BOOLEAN);

        if($userExists) {
            if ($users->count() === 0) {
                $this->error("The user by the email of \"$email\" could not be found.");
                return 0;
            }

            $user = $users->first();
        }

        if($userExists) {
            $saves = Save::whereUserId($user->id)->limit(3);
        } else {
            $saves = null;
        }

        $saveIds = [];
        if($saves !== null) {
            $this->info("{$saves->count()} Saves found for this user");

            foreach ($saves as $save) {
                $saveIds[] = $save->id;
                $pokes = Pokemon::whereSaveId($save->id);

                if ($pokes !== null && $pokes->count() > 0) {
                    $this->info("{$pokes->count()} Pokes found for save id {$save->id}");
                    $pokes->delete();
                }
            }
        } else {
            $this->info('You do not have the checkUserExists flag enabled so the saves for the user can not be retrieved.');
        }

        $pokes = Pokemon::whereEmail($email);

        if ($pokes !== null || $pokes->count() > 0) {
            $this->info("{$pokes->count()} Pokes found by email");
            $pokes->delete();
        }

        if($userExists) {
            $saves->delete();
        }

        $trades = DB::table('trades')->where('email', $email);
        $this->info("{$trades->count()} Trades found by email");

        $achievements = DB::table('achievements')->where('email', $email);
        $this->info("{$achievements->count()} Achievements found by email");

        foreach ($saveIds as $saveId) {
            $offers = DB::table('offers')
                ->where('requestSave', $saveId);
            $this->info("{$offers->count()} Offers found by requestSave (Requests)");
            $offers = DB::table('offers')
                ->where('offerSave', $saveId);
            $this->info("{$offers->count()} Offers found by offerSave (Offers)");
        }

        if($userExists) {
            $user->delete();
        }


        return 0;
    }
}
