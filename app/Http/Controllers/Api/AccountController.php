<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Web\ExcludeController;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use RateLimiter;

class AccountController extends ExcludeController {
    public function get(Request $request): array
    {
        $relations = $this->excludeRelations($request->input('exclude'), Collection::make(['saves', 'saves.pokemon', 'saves.pokemon.offers', 'saves.pokemon.requests', 'saves.items', 'achievement']));
        $attributes = $this->excludeAttributes($request->input('exclude'), Collection::make(array_keys(Auth::user()->getAttributes())));

        $user = User::with($relations->undot()->toArray())
            ->find(Auth::id(), $attributes->toArray());

        if(($save = $request->input('save')) !== null) {
            if($user instanceof User) {
                $nums = [0, 1, 2];

                $nums = array_diff($nums, [$save]);

                foreach ($nums as $num) {
                    $user->saves->pull($num);
                }
            }
        }

        return $user->toArray();
    }

    public function update(Request $request): array
    {
        $prohibited = ['id', 'email_verified_at', 'remember_token', 'role_id', 'created_at', 'updated_at'];

        foreach ($prohibited as $prohibit) {
            if($request->has($prohibit)) {
                return ['success' => false, 'error' => "The $prohibit column cannot be changed"];
            }
        }

        if(($email = $request->get('email')) !== null) {
            if(User::whereEmail($email)->exists()) {
                return ['success' => false, 'error' => 'You cannot change your account to this email as it is used by another account'];
            }

            $user = $request->user();
            $userId = $user->id;

            $trimmedEmail = trim($email);
            $emailUser = User::factory()->make(['id' => $userId, 'email' => $trimmedEmail]);

            // Don't change email until after the verification link is clicked
            RateLimiter::attempt(
                'email-change-verification-email:' . $userId,
                5,
                function() use ($trimmedEmail, $emailUser, $userId) {
                    if(Cache::set('email-change-verification-email:' . $userId, $trimmedEmail, 60*60)) {
                        $emailUser->sendEmailVerificationNotification();
                    }
                },
                // 5 Attempts PER HOUR
                60*60
            );

            $request->request->remove('email');
        }

        if(($password = $request->get('password')) !== null) {
            $array = $request->all();
            $array['password'] = Hash::make(strval($password));
            $request->replace($array);
        }

        if(!Auth::user()->update($request->toArray())) {
            return ['success' => false, 'error' => 'An unknown error occurred while trying to update the account'];
        }

        return ['success' => true];
    }
}
