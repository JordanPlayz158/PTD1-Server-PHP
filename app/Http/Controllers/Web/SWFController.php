<?php

namespace App\Http\Controllers\Web;

use App\Enums\Reason;
use App\Enums\Result;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SWF\SWFRequest;
use App\Http\Responses\Builders\SWF\LoadBuilder;
use App\Http\Responses\Builders\SWF\SWFBuilder;
use App\Models\User;
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

        return match ($action) {
            'loadAccount' => $this->loadAccount($user),
            'saveAccount' => $this->saveAccount($user),
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

        $loadResponse->getSave1();

        return $loadResponse->create();
    }

    private function saveAccount(User $user) {

    }
}
