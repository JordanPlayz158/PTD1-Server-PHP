<?php

namespace App\Http\Controllers\Web;

use App\Enums\Reason;
use App\Enums\Result;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SWF\SWFRequest;
use App\Http\Responses\Builders\SWF\Load\PokemonBuilder;
use App\Http\Responses\Builders\SWF\LoadBuilder;
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
            ->setAccNickname($user->name)
            ->setDex($user->dex)
            ->setShinyDex($user->shinyDex)
            ->setShadowDex($user->shadowDex);


        $saves = $user->saves()->orderBy('num')->get();

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

            $pokes = $save->pokes()->get();

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

            foreach(unserialize($save->items) as $item) {
                $saveBuilder->addItem($item);
            }
        }

        return $loadResponse->create();
    }

    private function saveAccount(User $user, string $save) {
        $saveRequest = FormRequest::create('/php/newPoke8.php', 'POST', [], [], [], [], $save);

        print_r($saveRequest->all());
    }
}
