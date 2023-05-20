<?php

namespace App\Http\Controllers\Web;

use App\Enums\Reason;
use App\Enums\Result;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SWF\SWFRequest;
use App\Http\Responses\Builders\SWF\AchievementBuilder;
use App\Http\Responses\Builders\SWF\SWFBuilder;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AchievementController extends Controller {
    public function post(SWFRequest $request): Response {
        if(app()->isDownForMaintenance()) {
            return SWFBuilder::new()->setResult(Result::FAILURE())->setReason(Reason::MAINTENANCE())->create();
        }

        $email = $request->input('Email');
        $password = $request->input('Pass');
        $action = $request->input('Action');

        if(!$request->authenticate()) {
            return SWFBuilder::new()->setResult(Result::FAILURE())->setReason(Reason::NOT_FOUND())->create();
        }

        $user = User::whereEmail($email)->first();
        $hashedPassword = $user->password;

        if(Hash::needsRehash($hashedPassword)) {
            $user->password = Hash::make($password);
            $user->save();
        }

        $achievements = $user->achievement()->first();

        if($achievements === null) {
            $achievements = Achievement::factory()->create(['user_id' => $user->id]);
        }

        switch ($action) {
            case 'checkAccount':
                $response = AchievementBuilder::new()->setResult(Result::SUCCESS())->setReason(Reason::GET_ACHIEVE());

                //need to respond with Ach1-Ach14
                for ($i = 1; $i < 15; $i++) {
                    $response->setAchievement($i, $achievements[$this->intNumberToString($i)]);
                }

                return $response->create();
            case 'updateAccount':
                /*
                 * type = achievement to be updated
                 * pos = char (position) to be updated (in string) (-1 for all chars)
                 */
                $num = $this->intNumberToString($request->input('type'));

                $pos = $request->input('pos');
                if ($pos == -1) {
                    $achievements->$num = 1;
                } else {
                    $split = str_split($achievements->$num);
                    for ($i = 0; $i < sizeof($split); $i++) {
                        // Need to find out if $pos count starts at 1 or 0
                        if ($i == $pos) {
                            $split[$i] = '1';
                            break;
                        }
                    }

                    $achievements->$num = implode($split);
                }

                $achievements->save();

                return SWFBuilder::new()->setResult(Result::SUCCESS())->create();
        }

        if (str_starts_with($action, 'get_Reward_')) {
            $number = strtolower(substr($action, 11));

            if (intval($number) != 0) {
                $number = $this->intNumberToString($number);
            }

            foreach (str_split($achievements->$number) as $num) {
                if ($num == 0) {
                    return SWFBuilder::new()->setResult(Result::FAILURE())->setReason(Reason::NO_REWARD())->create();
                }
            }

            // Need to code 7 and above as they send Pokémon to Pokémon center
            // So will need to do that on the backend
            return AchievementBuilder::new()->setResult(Result::SUCCESS())->setReason('getPrize' . $this->stringNumberToInt($number))->create();
        }

        return SWFBuilder::new()->setResult(Result::FAILURE())->setReason(Reason::INVALID_ACTION())->create();
    }

    function intNumberToString(int $num) : string {
        return match ($num) {
            1 => "one",
            2 => "two",
            3 => "three",
            4 => "four",
            5 => "five",
            6 => "six",
            7 => "seven",
            8 => "eight",
            9 => "nine",
            10 => "ten",
            11 => "eleven",
            12 => "twelve",
            13 => "thirteen",
            14 => "fourteen"
        };
    }

    function stringNumberToInt(string $num) : int
    {
        return match ($num) {
            "one" => 1,
            "two" => 2,
            "three" => 3,
            "four" => 4,
            "five" => 5,
            "six" => 6,
            "seven" => 7,
            "eight" => 8,
            "nine" => 9,
            "ten" => 10,
            "eleven" => 11,
            "twelve" => 12,
            "thirteen" => 13,
            "fourteen" => 14
        };
    }
}
