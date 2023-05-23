<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Giveaway;
use App\Models\GiveawayEntry;
use App\Models\GiveawayPokemon;
use App\Models\OfferPokemon;
use App\Models\Save;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GiveawayController extends Controller {
    public function create(Request $request)
    {
        $user = $request->user();

        if(!$user instanceof User) return ['success' => false, 'error' => 'user was not instance of User'];

        $timezone = $request->input('timezone', 'UTC');
        $endDate = Carbon::parse($request->input('endDate') . ' ' . $timezone);

        if($endDate->isBefore(Carbon::now()->addHour()) || $endDate->isAfter(Carbon::now()->addMonth()))
            return ['success' => false, 'error' => 'The date needs to be at least 1 hour after current time and no greater than 1 month past current date!'];

        $giveawayPokemon = [];

        foreach($request->all() as $key => $value) {
            if(str_starts_with($key, 'pokemon') && $user->ownsPokemon($value)) {
                if(GiveawayPokemon::where('pokemon_id', '=', $value)->count() !== 0) {
                    return ['success' => false, 'error' => 'Pokemon already used in another giveaway!'];
                }

                $giveawayPokemon[] = $value;
            }
        }

        if(sizeof($giveawayPokemon) <= 0) {
            return ['success' => false, 'error' => 'No pokemon put up for giveaway'];
        }

        // Make giveaway_pokemon entries first
        $lastId = Cache::get('lastGiveawayPokemonId');

        if($lastId === null) {
            $lastId = GiveawayPokemon::query()->latest()->first();

            if($lastId === null) {
                $lastId = 0;
            } else {
                $lastId = $lastId->id;
            }
        }

        $lastId++;

        foreach($giveawayPokemon as $pokemon) {
            GiveawayPokemon::create(['id' => $lastId, 'pokemon_id' => $pokemon]);
        }

        Cache::set('lastGiveawayPokemonId', $lastId);

        Giveaway::create(['owner_save_id' => $user->selectedSave()->id, 'giveaway_pokemon_id' => $lastId, 'complete_at' => $endDate]);

        return redirect('/games/ptd/giveaways.php');
    }

    public function join(Request $request, int $id)
    {
        $giveaway = Giveaway::find($id);
        if($giveaway === null) return ['success' => false, 'error' => 'Giveaway does not exist'];
        if(Carbon::parse($giveaway->complete_at)->isPast()) return ['success' => false, 'error' => 'Giveaway has ended'];

        if($request->user()->ownsSave($giveaway->owner_save_id)) return ['success' => false, 'error' => 'You can\'t join your own giveaway'];

        foreach ($giveaway->participants()->get('save_id') as $participant) {
            if($request->user()->ownsSave($participant->save_id)) {
                return ['success' => false, 'error' => 'Already joined giveaway.'];
            }
        };

        $entry = GiveawayEntry::firstOrCreate(['giveaway_id' => $giveaway->id, 'save_id' => $request->user()->selectedSave()->id]);

        if(!$entry->wasRecentlyCreated) return ['success' => false, 'error' => 'Already entered into Giveaway'];

        return ['success' => true];
    }

    public function leave(Request $request, int $id)
    {
        $giveaway = Giveaway::find($id);
        if($giveaway === null) return ['success' => false, 'error' => 'Giveaway does not exist'];
        if(Carbon::parse($giveaway->complete_at)->isPast()) return ['success' => false, 'error' => 'Giveaway has ended'];

        $entry = GiveawayEntry::where('giveaway_id', '=', $giveaway->id)
            ->where('save_id', '=', $request->user()->selectedSave()->id);

        if($entry->count() === 0) return ['success' => false, 'error' => 'Not entered into Giveaway'];

        $entry->delete();

        return ['success' => true];
    }

    public function cancel(Request $request, int $id)
    {
        $giveaway = Giveaway::find($id);
        if($giveaway === null) return ['success' => false, 'error' => 'Giveaway does not exist'];
        if(Carbon::parse($giveaway->complete_at)->isPast()) return ['success' => false, 'error' => 'Giveaway has ended'];
        if(Carbon::parse($giveaway->created_at)->addDay()->isBefore(Carbon::now())) return ['success' => false, 'error' => 'You may only cancel a giveaway within 24 hours of creating it'];
        if(!$request->user()->ownsSave($giveaway->owner_save_id)) return ['success' => false, 'error' => 'You can\'t cancel a giveaway you did not mske!'];

        GiveawayPokemon::where('id', '=', $giveaway->giveaway_pokemon_id)->delete();

        $giveaway->delete();

        return ['success' => true];
    }

    public static function completeGiveaways(): void
    {
        $completedGiveaways = Giveaway::where('complete_at', '<', Carbon::now())->whereNull('winner_save_id');

        foreach ($completedGiveaways->lazy() as $giveaway) {
            if(!$giveaway instanceof Giveaway) continue;

            // If there is no one to giveaway to, it is almost as if
            // the giveaway never exists, so delete it
            if($giveaway->participants()->count() === 0) $giveaway->delete();
            // TODO: ^
            //  If giveaway is deleted due to lack of participants
            //  inform the host of it via notification system


            $winner = $giveaway->participants()->inRandomOrder()->limit(1)->first();
            $winnerSaveId = $winner->id;
            $giveaway->winner_save_id = $winnerSaveId;

            // TODO: Once notification system is implemented
            //  send the winner a notification that they won
            //  the giveaway


            foreach($giveaway->pokemon()->lazy() as $pokemon) {
                $pokemon->pId = self::getUniquePokemonId($winner->allPokemon());
                $pokemon->save_id = $winnerSaveId;
                $pokemon->save();
            }

            $giveaway->save();
        }
    }

    private static function getUniquePokemonId(HasMany $pokemon) : int
    {
        $valid = false;
        $tmp = -1;

        while (!$valid) {
            // Integer limit, would be great if swf used Number for 64 bit and not int but...
            // Got to work with what you are given (otherwise I could just use auto_increment id)
            $tmp = mt_rand(1, 2147483647);
            $valid = true;

            foreach ($pokemon->select('pId')->lazy() as $poke) {
                if ($tmp == $poke->pId) {
                    $valid = false;
                    break;
                }
            }
        }

        return $tmp;
    }
}
