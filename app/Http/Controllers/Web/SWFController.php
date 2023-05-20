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
use Illuminate\Auth\Events\Registered;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Log;

class SWFController extends Controller {
    # Array of ASCII Chars that turns into the AES KEY
    private static array $aesKeyArray = [99,124,119,123,242,107,111,197,48,1,103,43,254,215,171,118,202,130,201,125,250,89,71,240,173,212,162,175,156,164,114,192,183,253,147,38,54,63,247,204,52,165,229,241,113,216,49,21,4,199,35,195,24,150,5,154,7,18,128,226,235,39,178,117,9,131,44,26,27,110,90,160,82,59,214,179,41,227,47,132,83,209,0,237,32,252,177,91,106,203,190,57,74,76,88,207,208,239,170,251,67,77,51,133,69,249,2,127,80,60,159,168,81,163,64,143,146,157,56,245,188,182,218,33,16,255,243,210,205,12,19,236,95,151,68,23,196,167,126,61,100,93,25,115,96,129,79,220,34,42,144,136,70,238,184,20,222,94,11,219,224,50,58,10,73,6,36,92,194,211,172,98,145,149,228,121,231,200,55,109,141,213,78,169,108,86,244,234,101,122,174,8,186,120,37,46,28,166,180,198,232,221,116,31,75,189,139,138,112,62,181,102,72,3,246,14,97,53,87,185,134,193,29,158,225,248,152,17,105,217,142,148,155,30,135,233,206,85,40,223,140,161,137,13,191,230,66,104,65,153,45,15,176,84,187,22];


    private string $aesKey = '';
    private Encrypter $encryptor;

    public function __construct() {
        /*foreach (SWFController::$aesKeyArray as $keyByte) {
            $this->aesKey .= chr($keyByte);
        }

        Log::debug('AES Key Length', [strlen($this->aesKey)]);
        Log::debug('AES Key Length in 8-bit?', [mb_strlen($this->aesKey, '8bit')]);

        $this->encryptor = new Encrypter($this->aesKey, 'aes-256-cbc');*/
    }

    public function post(SWFRequest $request): Response {
        if(app()->isDownForMaintenance()) {
            return SWFBuilder::new()->setResult(Result::FAILURE())->setReason(Reason::MAINTENANCE())->create();
        }

        Log::info('Request:', [request()->all()]);


        $email = $request->input('Email');
        $password = $request->input('Pass');
        $action = $request->input('Action');
        $version = $request->input('ver');



        if($action === 'createAccount') {
            $createAccountResponse = $this->createAccount($email, $password);

            if($version == 650) {
                return $this->encryptedResponse($createAccountResponse);
            }

            return $createAccountResponse;
        }

        if(!$request->authenticate()) {
            $failedAuthenticationResponse = SWFBuilder::new()->setResult(Result::FAILURE())->setReason(Reason::NOT_FOUND())->create();

            if($version == 650) {
                return $this->encryptedResponse($failedAuthenticationResponse);
            }

            return $failedAuthenticationResponse;
        }

        $user = User::whereEmail($email)->first();
        $hashedPassword = $user->password;

        if(Hash::needsRehash($hashedPassword)) {
            $user->password = Hash::make($password);
            $user->save();
        }

        // Might use this
        //$request->whenHas('saveString')

        $response = match ($action) {
            'loadAccount' => $this->loadAccount($user),
            'saveAccount' => $this->saveAccount($user, $request->input('saveString')),
            default => SWFBuilder::new()->setResult(Result::FAILURE())->setReason(Reason::INVALID_ACTION())->create()
        };

        if($version == 650) {
            return $this->encryptedResponse($response);
        }

        return $response;
    }

    private function createAccount(string $email, string $password): Response {
        // TODO: Users with invalid emails should not be created

        $user = User::firstOrCreate(['email' => $email], ['password' => Hash::make($password)]);

        event(new Registered($user));

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

        $userSave = $user->saves()->where('num', '=', $saveNum)->firstOrNew();

        if(!($userSave instanceof Save)) {
            return SWFBuilder::new()->setResult(Result::FAILURE())->create();
        }

        if(isset($save['newGame']) && $save['newGame'] == 'yes') {
            // Ensure to account for all non-foreign key-able tables
            // such as offers

            if(!$userSave->wasRecentlyCreated) {
                $userSave->delete();
            }
            $userSave = new Save();
            $userSave->user_id = $user->id;
            $userSave->num = $saveNum;
            $userSave->save();
        } else {
            if (isset($save['releasePoke'])) {
                $releasePokes = explode('|', $save['releasePoke']);
                $releasePokes = array_unique($releasePokes);
                $releasePokes = array_values($releasePokes);

                if (($key = array_search('0', $releasePokes)) !== false) {
                    unset($releasePokes[$key]);
                }

                foreach($releasePokes as $releasePoke) {
                    $userSave->pokemon()->where('pId', '=', $releasePoke)->delete();
                }
            }
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

        for ($i = 1; $i <= intval($save['HMP']); $i++) {
            $pokeNum = 'poke' . $i . '_';
            $pokeId = $pokeNum . 'myID';

            $tradePokemon = $userSave->allPokemon()->where('pId', '=', $save[$pokeId])->first();
            if($tradePokemon !== null && $tradePokemon->isUpForTrade()) {
                continue;
            }

            // This is not finding a Pokémon, so it is returning a new one
            $poke = $userSave->pokemon()->where('pId', '=', $save[$pokeId])->first();

            if($poke === null) {
                $poke = new Pokemon();
                $poke->wasRecentlyCreated = true;
            }

            if (isset($releasePokes) && in_array($save[$pokeId], $releasePokes)) {
                continue;
            }

            $pokeExisted = isset($poke->pId);

            $pokeNewId = false;
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

                $poke = $poke->make(['save_id' => $userSave->id, 'pId' => $tmp]);
                $pokeNewId = true;
            }

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
                        break;
                    case 'evolve':
                        $poke->pNum = $save[$pokeNum . 'num'];
                        $poke->nickname = $save[$pokeNum . 'nickname'];
                        break;
                    case 'exp':
                        $poke->exp = $save[$pokeNum . 'exp'];
                        break;
                    case 'pos':
                        $poke->pos = $save[$pokeNum . 'pos'];
                        break;
                    case 'lvl':
                        $poke->lvl = $save[$pokeNum . 'lvl'];
                        break;
                    case 'moves':
                        $poke->m1 = $save[$pokeNum . 'm1'];
                        $poke->m2 = $save[$pokeNum . 'm2'];
                        $poke->m3 = $save[$pokeNum . 'm3'];
                        $poke->m4 = $save[$pokeNum . 'm4'];
                        break;
                    case 'tag':
                        $poke->tag = $save[$pokeNum . 'tag'];
                        break;
                    case 'target':
                        $poke->targetType = $save[$pokeNum . 'targetType'];
                        break;
                    case 'mSel':
                        $poke->mSel = $save[$pokeNum . 'mSel'];
                }
            }

            //if (!$pokeExisted) {
                /*
                if($poke->shiny == 1)
                    $save->p_hs++;
                */
                //$save->p_hs += ($poke->shiny == 1);
            //}

            if($pokeNewId) {
                $saveResponse->addNewPokePosition($poke->pos, $poke->pId);
            }

            if(!$poke->save()) {
                Log::info('The pokemon failed to save', [$poke]);
            }
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

    private function encryptedResponse(Response $plainResponse) : Response {
        return response($this->encryptor->encryptString($plainResponse));
    }
}
