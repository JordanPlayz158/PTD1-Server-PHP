<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Giveaway;
use App\Models\GiveawayEntry;
use App\Models\GiveawayPokemon;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class GiveawayController extends Controller {
    public function create(Request $request)
    {
        $type = \App\Enums\Giveaway::coerce(intval($request->input('type', 0)));

        $user = $request->user();

        if(!$user instanceof User) return ['success' => false, 'error' => 'user was not instance of User'];

        $endDateString = $request->input('endDate');

        if(strlen($endDateString) === 0) return ['success' => false, 'error' => 'Invalid end date'];

        $timezone = $request->input('timezone', 'UTC');
        $endDate = Carbon::parse($endDateString . $timezone)->setTimezone('UTC');
        $currentDate = Carbon::now()->setTimezone('UTC');

        if($endDate->isBefore($currentDate->copy()->addHour()) || $endDate->isAfter($currentDate->copy()->addMonth()))
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

        $giveaway = Giveaway::create(['type' => $type, 'title' => $request->input('title'), 'owner_save_id' => $user->selectedSave()->id, 'complete_at' => $endDate]);

        // Make giveaway_pokemon entries first
        foreach($giveawayPokemon as $pokemon) {
            GiveawayPokemon::create(['id' => $giveaway->id, 'pokemon_id' => $pokemon]);
        }

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
        if(Carbon::parse($giveaway->created_at)->addDay()->isBefore(Carbon::now('UTC'))) return ['success' => false, 'error' => 'You may only cancel a giveaway within 24 hours of creating it'];
        if(!$request->user()->ownsSave($giveaway->owner_save_id)) return ['success' => false, 'error' => 'You can\'t cancel a giveaway you did not mske!'];

        GiveawayPokemon::where('id', '=', $giveaway->id)->delete();

        $giveaway->delete();

        return ['success' => true];
    }

    public static function completeGiveaways(): void
    {
        $completedGiveaways = Giveaway::where('complete_at', '<', Carbon::now('UTC'))
            ->whereNotExists(function (Builder $query) {
                $query->from('giveaway_entries')
                    ->whereColumn('giveaways.id', '=', 'giveaway_entries.giveaway_id')
                    ->where('giveaway_entries.winner', '=', true);
            });

        foreach ($completedGiveaways->lazy() as $giveaway) {
            if(!$giveaway instanceof Giveaway) continue;

            // If there is no one to giveaway to, it is almost as if
            // the giveaway never exists, so delete it
            if($giveaway->participants()->count() === 0) {
                $giveaway->pokemon()->delete();
                $giveaway->delete();
                continue;
            }
            // TODO: ^
            //  If giveaway is deleted due to lack of participants
            //  inform the host of it via notification system


            $numberOfWinners = match($giveaway->type) {
                \App\Enums\Giveaway::SINGLE_WINNER => 1,
                \App\Enums\Giveaway::MULTIPLE_WINNERS => $giveaway->pokemon()->count(),
                default => throw new Exception('Unknown Giveaway Enum')
            };

            for ($i = 0; $i < $numberOfWinners; $i++) {
                $winner = $giveaway->participants()->inRandomOrder()->limit(1)->first();
                $winnerSave = $winner->entrySave()->first();

                // No composite key support for Eloquent (yet (I hope it is a yet and not never))
                \DB::table('giveaway_entries')
                    ->where('giveaway_id', '=', $winner->giveaway_id)
                    ->where('save_id', '=', $winner->save_id)
                    ->update(['winner' => true]);

                // TODO: Once notification system is implemented
                //  send the winner a notification that they won
                //  the giveaway


                $giveawayPokemon = $giveaway->pokemon()->limit(1)->offset($i)->first();
                $pokemon = $giveawayPokemon->pokemon()->first();
                $pokemon->pId = self::getUniquePokemonId($winnerSave->allPokemon());
                $pokemon->save_id = $winnerSave->id;
                $pokemon->save();
            }
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
