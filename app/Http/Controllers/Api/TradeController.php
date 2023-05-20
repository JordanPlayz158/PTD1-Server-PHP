<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Web\ExcludeController;
use App\Models\Pokemon;
use App\Models\Save;
use App\Models\Trade;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class TradeController extends ExcludeController {
    public function get(Request $request)
    {
        $upForTradePokemon = Collection::empty();

        Auth::user()->saves()->each(function (Save $save) use ($upForTradePokemon) {
            $upForTradePokemon->push($save->allPokemon()->get()->reject(function (Pokemon $pokemon) {
                return !$pokemon->isUpForTrade();
            })->values());
        });

        return $upForTradePokemon;
    }

    public function getSaveTrades($num) {
        return Auth::user()->saves()->where('num', '=', $num)->first()->allPokemon()->get()->reject(function (Pokemon $pokemon) {
            return !$pokemon->isUpForTrade();
        })->values();
    }

    public function all() {
        return Pokemon::query()->whereExists(function (Builder $query) {
            $query->from('trades')->whereColumn('poke_id', '=', 'pokemon.id');
        })->get();
    }

    public function create(Request $request)
    {
        $request->validate(['pokemon_id' => 'required|numeric|integer']);

        $pokemonId = $request->get('pokemon_id');

        $pokemon = null;

        foreach(Auth::user()->saves()->get() as $save) {
            foreach ($save->allPokemon()->get() as $poke) {
                if($poke->id === $pokemonId) {
                    $pokemon = $poke;
                    break 2;
                }
            }
        }

        if($pokemon === null) {
            return ['success' => false, 'error' => 'You must be the owner of the pokemon to put it up for trade'];
        }

        if($pokemon->isUpForTrade()) {
            return ['success' => false, 'error' => 'Your pokemon is already up for trade'];
        }

        $trade = new Trade();

        $trade->poke_id = $pokemonId;

        if(!$trade->save()) {
            return ['success' => false, 'error' => 'An unknown error occurred while trying to create the trade'];
        }

        return ['success' => true];
    }

    public function remove(Request $request) {
        $request->validate(['pokemon_id' => 'required|numeric|integer']);

        $pokemonId = $request->get('pokemon_id');

        $pokemon = null;

        foreach(Auth::user()->saves()->get() as $save) {
            foreach ($save->allPokemon()->get() as $poke) {
                if($poke->id === $pokemonId) {
                    $pokemon = $poke;
                    break 2;
                }
            }
        }

        if($pokemon === null) {
            return ['success' => false, 'error' => 'You must be the owner of the pokemon to make it unavailable to trade'];
        }

        if(!$pokemon->isUpForTrade()) {
            return ['success' => false, 'error' => 'Your pokemon is not up for trade'];
        }

        if(!$pokemon->trade()->delete()) {
            return ['success' => false, 'error' => 'An unknown error occurred while trying to delete the trade'];
        }

        // TODO: If a user removes a pokemon from trade, ensure any offers for that pokemon get removed.

        $evolve = match ($pokemon->pNum) {
            // Kadabra => Alakazam
            64 => [65, 'Alakazam'],
            // Machoke => Machamp
            67 => [68, 'Machamp'],
            // Graveler => Golem
            75 => [76, 'Golem'],
            // Haunter => Gengar
            93 => [94, 'Gengar'],

            default => [-1],
        };

        if($evolve[0] !== -1) {
            $pokemon->pNum = $evolve[0];
            $pokemon->nickname = $evolve[1];
            $pokemon->save();
        }

        return ['success' => true];
    }
}
