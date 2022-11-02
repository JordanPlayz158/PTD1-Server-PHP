<?php

namespace App\Http\Controllers\Web;

use App\Enums\Reason;
use App\Enums\Result;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SWF\SWFRequest;
use App\Http\Responses\Builders\SWF\Load\PokemonBuilder;
use App\Http\Responses\Builders\SWF\LoadBuilder;
use App\Http\Responses\Builders\SWF\SaveBuilder;
use App\Http\Responses\Builders\SWF\SWFBuilder;
use App\Models\Pokemon;
use App\Models\Save;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class SWFController extends Controller {
    public function post(SWFRequest $request): Response {
        $email = $request->input('Email');
        $password = $request->input('Pass');
        $action = $request->input('Action');

        if($action === 'createAccount') return $this->createAccount($email, $password);

        if(!$request->authenticate()) {
            return SWFBuilder::new()->setResult(Result::FAILURE())->setReason(Reason::NOT_FOUND())->create();
        }

        $user = User::whereEmail($email)->first();
        $hashedPassword = $user->password;

        if(Hash::needsRehash($hashedPassword)) {
            $user->password = Hash::make($password);
            $user->save();
        }

        // Might use this
        //$request->whenHas('saveString')

        return match ($action) {
            'loadAccount' => $this->loadAccount($user),
            'saveAccount' => $this->saveAccount($user, $request->input('saveString')),
            default => SWFBuilder::new()->setResult(Result::FAILURE())->setReason(Reason::INVALID_ACTION())->create()
        };
    }

    private function createAccount(string $email, string $password): Response {
        $user = User::firstOrCreate(['email' => $email], ['password' => Hash::make($password)]);

        // Means it got the first user in db (which means the record exists) and didn't make a new one
        if(!$user->wasRecentlyCreated) {
            return SWFBuilder::new()->setResult(Result::FAILURE())->setReason(Reason::TAKEN())->create();
        }

        return $this->loadAccount($user);
    }

    private function loadAccount(User $user): Response {
        $loadResponse = LoadBuilder::new()
            ->setResult(Result::SUCCESS())
            ->setReason(Reason::LOGGED_IN())
            ->setAccNickname($user->getNameAttribute())
            ->setDex($user->dex)
            ->setShinyDex($user->shinyDex)
            ->setShadowDex($user->shadowDex);

        $saves = $user->saves()->orderBy('num')->get();

        $nums = [0, 1, 2];
        $usedNums = [];

        foreach ($saves->all() as $save) {
            $usedNums[] = $save->num;
        }

        $populateNums = array_diff($nums, $usedNums);

        $savesCopy = $saves;

        foreach ($populateNums as $num) {
            $savesCopy->add(new Save(['num' => $num]));
        }

        foreach ($savesCopy as $save) {
            $saves->put($save->num, $save);
        }


        for($i = 0; $i < sizeof($saves); $i++) {
            $save = $saves[$i];
            if(!($save instanceof Save)) continue;

            $saveBuilder = $loadResponse->getSave($i);

            if(!$saveBuilder) continue;

            $saveBuilder->setAdvanced($save->advanced)
                ->setAdvancedA($save->advanced_a)
                ->setNickname($save->nickname)
                ->setBadges($save->badges)
                ->setAvatar($save->avatar)
                ->setClassic($save->classic)
                ->setClassicA($save->classic_a)
                ->setChallenge($save->challenge)
                ->setMoney($save->money)
                ->setNPCTrade($save->npcTrade)
                ->setShinyHunt($save->shinyHunt)
                ->setVersion($save->version);

            $pokes = $save->pokemon()->get();

            for($ii = 0; $ii < sizeof($pokes); $ii++) {
                $poke = $pokes[$ii];
                if(!($poke instanceof Pokemon)) continue;

                $saveBuilder->addPokemon(PokemonBuilder::new($save->num + 1, $ii + 1)
                    ->setNickname($poke->nickname)
                    ->setNum($poke->pNum)
                    ->setLvl($poke->lvl)
                    ->setExp($poke->exp)
                    ->setOwner($poke->owner)
                    ->setTargetType($poke->targetType)
                    ->setTag($poke->tag)
                    ->setMyID($poke->pId)
                    ->setPos($poke->pos)
                    ->setShiny($poke->shiny)
                    ->setM1($poke->m1)
                    ->setM2($poke->m2)
                    ->setM3($poke->m3)
                    ->setM4($poke->m4)
                    ->setMSel($poke->mSel));
            }

            foreach($save->items()->get() as $item) {
                $saveBuilder->addItem($item->item);
            }
        }

        return $loadResponse->create();
    }

    private function saveAccount(User $user, string $saveString) : Response {
        parse_str($saveString, $save);

        $saveResponse = SaveBuilder::new()
            ->setResult(Result::SUCCESS());

        // We trim all the trailing 0s as we repopulate them before
        // sending, and it is more space efficient when no data is being
        // lost by trimming the trailing 0s in the string
        $user->dex = rtrim($save['dex1'], '0');
        $user->shinyDex = rtrim($save['dex1Shiny'], '0');
        $user->shadowDex = rtrim($save['dex1Shadow'], '0');

        $user->dex = empty($user->dex) ? null : $user->dex;
        $user->shinyDex = empty($user->shinyDex) ? null : $user->shinyDex;
        $user->shadowDex = empty($user->shadowDex) ? null : $user->shadowDex;

        $saveNum = (intval($save['whichProfile']) - 1);

        $userSave = $user->saves()->where('num', '=', $saveNum)->first();

        if($userSave === null) {
            $userSave = new Save();
        }

        if(!($userSave instanceof Save)) {
            return SWFBuilder::new()->setResult(Result::FAILURE())->create();
        }

        if(isset($save['newGame']) && $save['newGame'] == 'yes') {
            // Ensure to account for all non-foreign key-able tables
            // such as offers

            $userSave->delete();
            $userSave = new Save();
            $userSave->user_id = $user->id;
            $userSave->num = $saveNum;
            $userSave->save();
        }

        $userSave->advanced = $save['a_story'];
        $userSave->advanced_a = $save['a_story_a'];
        $userSave->classic = $save['c_story'];
        $userSave->classic_a = $save['c_story_a'];
        $userSave->badges = intval($save['badges']);
        $userSave->challenge = intval($save['challenge']);
        $userSave->npcTrade = intval($save['NPCTrade']);
        $userSave->shinyHunt = intval($save['ShinyHunt']);
        $userSave->money = intval($save['Money']);
        $userSave->nickname = $save['Nickname'] == 'Satoshi' ? null : $save['Nickname'];
        $userSave->version = intval($save['Version']);
        $userSave->avatar = $save['Avatar'];

        if (isset($save['releasePoke'])) {
            $releasePokes = explode('|', $save['releasePoke']);
        }

        for ($i = 1; $i <= intval($save['HMP']); $i++) {
            $pokeNum = 'poke' . $i . '_';
            // This is not finding a Pokémon, so it is returning a new one
            $poke = $userSave->pokemon()->where('pId', '=', $save[$pokeNum . 'myID'])->firstOrNew();

            if(!($poke instanceof Pokemon)) {
                return SaveBuilder::new()->setResult(Result::FAILURE())->create();
            }

            if (isset($releasePokes) && in_array($save[$pokeNum . 'myID'], $releasePokes)) {
                /*
                if ($poke -> shiny == 1)
                    $save -> p_hs--;
                */
                //$save->p_hs -= ($poke->shiny == 1);

                // drop offers containing poke id

                // If it is new and not in db, `wasRecentlyCreated` would be true
                if(!$poke->wasRecentlyCreated) {
                    $poke->delete();
                }

                continue;
            }

            $pokeExisted = isset($poke->pId);

            if (!$pokeExisted || $poke->pId == 0) {
                $valid = false;
                $tmp = -1;

                while (!$valid) {
                    // Integer limit, would be great if swf used Number for 64 bit and not int but...
                    // Got to work with what you are given (otherwise I could just use auto_increment id)
                    $tmp = mt_rand(1, 2147483647);
                    $valid = true;

                    foreach ($userSave->pokemon()->select('pId')->lazy() as $poke) {
                        if ($tmp == $poke->pId) {
                            $valid = false;
                            break;
                        }
                    }
                }

                $poke->pId = $tmp;
            }

            // TODO: Don't save Pokémon if on trade list
            if (isset($save[$pokeNum . 'extra'])) {
                $poke->shiny = match ($save[$pokeNum . 'extra']) {
                    /* Shiny
                     * Geodude & Graveler = 1
                     * Magnemite & Magneton = 2
                     * Tentacool & Tentacruel = 3
                     * Onix = 4
                     * Staryu & Starmie = 5
                     * Voltorb & Electrode = 6
                     * Hitmonlee & Hitmonchan = 153
                     * Omanyte & Omastar & Kabuto & Kabutops = 168
                     * Missing No. = 182
                     * Articuno & Zapdos & Moltres = 854
                     * Generic = 151
                     */
                    '1', '2', '3', '4', '5', '6', '151', '153', '168', '182', '854' => 1,
                    /* Shadow
                     * Lickitung = 180
                     * Articuno & Zapdos & Moltres = 855
                     * Generic = 555
                     */
                    '180', '555', '855' => 2,
                    /* Normal
                     * Hitmonlee & Hitmonchan = 152
                     * Missing No. = 181
                     * Mew = 201
                     * Omanyte & Omastar & Kabuto & Kabutops = 154
                     * Articuno & Zapdos & Moltres = 857
                     * Generic = 0
                     *
                     * Assuming anything else is normal at the moment
                     */
                    default => 0,
                };
            }

            $reasons = explode('|', $save[$pokeNum . 'reason']);
            foreach ($reasons as $reason) {
                switch ($reason) {
                    case 'cap':
                        // MyID
                        if(isset($save[$pokeNum . 'shiny'])) {
                            $poke->shiny = $save[$pokeNum . 'shiny'];
                        }

                        // No break as cap sends everything and trade almost does, so it's better for
                        // reducing code redundancy to let trade handle it
                    case 'trade':
                        $poke->pNum = $save[$pokeNum . 'num'];
                        $poke->nickname = $save[$pokeNum . 'nickname'];
                        $poke->exp = $save[$pokeNum . 'exp'];
                        $poke->lvl = $save[$pokeNum . 'lvl'];

                        $poke->m1 = $save[$pokeNum . 'm1'];
                        $poke->m2 = $save[$pokeNum . 'm2'];
                        $poke->m3 = $save[$pokeNum . 'm3'];
                        $poke->m4 = $save[$pokeNum . 'm4'];

                        $poke->ability = $save[$pokeNum . 'ability'];
                        $poke->mSel = $save[$pokeNum . 'mSel'];
                        $poke->targetType = $save[$pokeNum . 'targetType'];
                        $poke->tag = $save[$pokeNum . 'tag'];
                        $poke->item = $save[$pokeNum . 'item'];
                        $poke->owner = $save[$pokeNum . 'owner'];
                        $poke->pos = $save[$pokeNum . 'pos'];

//                        $columnsToModify[0] .= 'isiiiiiiiiisssi';
//                        array_push($columnsToModify);
                        break;
                    case 'evolve':
                        $poke->pNum = $save[$pokeNum . 'num'];
                        $poke->nickname = $save[$pokeNum . 'nickname'];

//                        $columnsToModify[0] .= 'is';
//                        array_push($columnsToModify);
                        break;
                    case 'exp':
                        $poke->exp = $save[$pokeNum . 'exp'];

//                        $columnsToModify[0] .= 'i';
//                        $columnsToModify[] = 'exp';
                        break;
                    case 'pos':
                        $poke->pos = $save[$pokeNum . 'pos'];

//                        $columnsToModify[0] .= 'i';
//                        $columnsToModify[] = 'pos';
                        break;
                    case 'lvl':
                        $poke->lvl = $save[$pokeNum . 'lvl'];

//                        $columnsToModify[0] .= 'i';
//                        $columnsToModify[] = 'lvl';
                        break;
                    case 'moves':
                        $poke->m1 = $save[$pokeNum . 'm1'];
                        $poke->m2 = $save[$pokeNum . 'm2'];
                        $poke->m3 = $save[$pokeNum . 'm3'];
                        $poke->m4 = $save[$pokeNum . 'm4'];

//                        $columnsToModify[0] .= 'iiii';
//                        array_push($columnsToModify, 'm1', 'm2', 'm3', 'm4');
                        break;
                    case 'tag':
                        $poke->tag = $save[$pokeNum . 'tag'];

//                        $columnsToModify[0] .= 's';
//                        $columnsToModify[] = 'tag';
                        break;
                    case 'target':
                        $poke->targetType = $save[$pokeNum . 'targetType'];

//                        $columnsToModify[0] .= 'i';
//                        $columnsToModify[] = 'targetType';
                        break;
                    case 'mSel':
                        $poke->mSel = $save[$pokeNum . 'mSel'];

//                        $columnsToModify[0] .= 'i';
//                        $columnsToModify[] = 'mSel';
                }
            }

            //if (!$pokeExisted) {
                /*
                if($poke->shiny == 1)
                    $save->p_hs++;
                */
                //$save->p_hs += ($poke->shiny == 1);
            //}

            $saveResponse->addNewPokePosition($poke->pos, $poke->pId);

            $poke->save();
        }

        /*
         * We clear the list of items gathered from the db
         * as the swf sends all the items in every save request
         * so if we didn't clear the array, the items would be duplicated by 2 every time the person saves,
         * which could cause something like this....
         * https://cdn.jordanplayz158.xyz/uploads/db645002741a1f21f1787f60199e3a8548e83dc9.png (screenshot of save with 1144 items in server response)
         * https://cdn.jordanplayz158.xyz/uploads/2df7bb33b7040c9b02f04dc780c50049ce765b7e.png (serialized array of items in database with size of 3962)
         * https://cdn.jordanplayz158.xyz/uploads/ad4df5ed9aa2e6a024ddf05e5190b10a55e56293.png (serialized array of items in database with size of 324)
         * https://cdn.jordanplayz158.xyz/uploads/3f52fb0489544ff69f090e3fd8e27152337bbb7f.png (especially this lol) (serialized array of items in database with size of 523776)
         * https://cdn.jordanplayz158.xyz/uploads/a81b46ea69dc2b9f0921bcb9bf003e0b1436626d.png (2 serialized arrays of items in database with sizes of 157000 and 131220)
         */
        $userSave->items()->delete();

        for ($i = 1; $i <= intval($save['HMI']); $i++) {
            $userSave->items()->insert(['save_id' => $userSave->id, 'item' => $save['item' . $i . '_num']]);
        }

        $userSave->save();

        $user->save();

        return $saveResponse->create();
    }
}
