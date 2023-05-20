<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Web\ExcludeController;
use App\Models\Pokemon;
use App\Models\Save;
use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PokemonController extends ExcludeController {
    public function get(Request $request, int $num)
    {
        $save = Auth::user()->saves()->where('num', '=', $num)->first();

        if($save === null) {
            return [];
        }

        $pokemon = $save->pokemon();

        $relations = $this->excludeRelations($request->input('exclude'), Collection::make(['offers', 'requests', 'offers.offerPokemon', 'offers.requestPokemon', 'requests.offerPokemon', 'requests.requestPokemon', 'offers.offerPokemon.pokemon', 'offers.requestPokemon.pokemon', 'requests.offerPokemon.pokemon', 'requests.requestPokemon.pokemon']));
        $attributes = $this->excludeAttributes($request->input('exclude'), Collection::make(array_keys($pokemon->first()->getAttributes())));

        $pokemon->each(function (Pokemon $pokemon) {});

        return $pokemon->with($relations->undot()->toArray())
            ->select($attributes->toArray())->paginate(100);
    }

    public function getPokemon(Request $request, int $num, int $id): array
    {
        $save = Auth::user()->saves()->where('num', '=', $num)->first();

        if($save === null) {
            return [];
        }

        $pokemon = $save->pokemon();

        $relations = $this->excludeRelations($request->input('exclude'), Collection::make(['offers', 'requests', 'offers.offerPokemon', 'offers.requestPokemon', 'requests.offerPokemon', 'requests.requestPokemon', 'offers.offerPokemon.pokemon', 'offers.requestPokemon.pokemon', 'requests.offerPokemon.pokemon', 'requests.requestPokemon.pokemon']));
        $attributes = $this->excludeAttributes($request->input('exclude'), Collection::make(array_keys($pokemon->first()->getAttributes())));

        $pokemon = $pokemon->where('id', '=', $id)->with($relations->undot()->toArray())
            ->select($attributes->toArray())->get()->first();

        return $pokemon->toArray();
    }

    public function all(Request $request, int $num): array
    {
        $save = Auth::user()->saves()->where('num', '=', $num)->first();

        if($save === null) {
            return [];
        }

        $pokemon = $save->allPokemon();

        $relations = $this->excludeRelations($request->input('exclude'), Collection::make(['offers', 'requests', 'offers.offerPokemon', 'offers.requestPokemon', 'requests.offerPokemon', 'requests.requestPokemon', 'offers.offerPokemon.pokemon', 'offers.requestPokemon.pokemon', 'requests.offerPokemon.pokemon', 'requests.requestPokemon.pokemon']));
        $attributes = $this->excludeAttributes($request->input('exclude'), Collection::make(array_keys($pokemon->first()->getAttributes())));

        $pokemon->each(function (Pokemon $pokemon) {});

        $pokemon = $pokemon->with($relations->undot()->toArray())
            ->select($attributes->toArray())->get();

        return $pokemon->toArray();
    }

    public function anyPokemon(Request $request, int $id): array
    {
        $relations = $this->excludeRelations($request->input('exclude'), Collection::make(['offers', 'requests', 'offers.offerPokemon', 'offers.requestPokemon', 'requests.offerPokemon', 'requests.requestPokemon', 'offers.offerPokemon.pokemon', 'offers.requestPokemon.pokemon', 'requests.offerPokemon.pokemon', 'requests.requestPokemon.pokemon']));
        $attributes = $this->excludeAttributes($request->input('exclude'), Collection::make(array_keys(Auth::user()->saves()->first()->pokemon()->first()->getAttributes())));

        $pokemon = Pokemon::with($relations->undot()->toArray())
            ->select($attributes->toArray())->where('id', '=', $id)->get()->first();

        if($pokemon === null) {
            return ['success' => false, 'error' => 'Pokemon does not exist'];
        }

        return $pokemon->toArray();
    }

    public function tradePokemon(Request $request, int $id) {
        $pokemon = Pokemon::whereId($id)->first();

        if($pokemon === null) return ['success' => false, 'error' => 'Pokemon does not exist'];
        if(!$pokemon->isUpForTrade()) return ['success' => false, 'error' => 'Pokemon is not up for trade'];

        return $this->anyPokemon($request, $id);
    }

    public function remove(Request $request, int $num, int $id): array
    {
        $user = $request->user();

        if(!($user instanceof User)) return ['success' => false];

        $save = $user->saves()->where('num', '=', $num)->first();

        if(!($save instanceof Save)) return ['success' => false];

        if($save->pokemon()->where('id', '=', $id)->first()->delete()) {
            return ['success' => true];
        }

        return ['success' => false];
    }
}
