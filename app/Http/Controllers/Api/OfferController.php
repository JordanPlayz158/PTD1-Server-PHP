<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Web\ExcludeController;
use App\Models\Offer;
use App\Models\OfferPokemon;
use App\Models\Pokemon;
use Auth;
use Illuminate\Http\Request;

class OfferController extends ExcludeController {
    public function create(Request $request, int $id)
    {
        $request->validate(['offerIds' => 'required|array']);

        $requestPoke = Pokemon::where('id', '=', $id)->first();

        if($requestPoke === null) {
            return ['success' => false, 'error' => 'The requested pokemon does not exist'];
        }

        if(!$requestPoke->isUpForTrade()) {
            return ['success' => false, 'error' => 'You can only make offers for pokemon that are up for trade'];
        }

        $offerIds = $request->get('offerIds');

        $lastSaveId = -1;
        foreach ($offerIds as $offerId) {
            if(filter_var($offerId, FILTER_VALIDATE_INT) === false) {
                return ['success' => false, 'error' => "offerId '$offerId' is not a valid integer"];
            }

            $pokemon = Pokemon::where('id', '=', $offerId)->first();

            if($pokemon === null) {
                return ['success' => false, 'error' => 'Pokemon does not exist'];
            }

            $ownerSave = $pokemon->ownerSave()->first();

            if($ownerSave->user()->first()->id !== Auth::id()) {
                return ['success' => false, 'error' => 'You must own the pokemon to put it up as an offer'];
            }

            $saveId = $ownerSave->id;

            if($lastSaveId !== -1 && $saveId !== $lastSaveId) {
                return ['success' => false, 'error' => 'All pokemon offered must be under the same save'];
            }

            $lastSaveId = $saveId;
        }

        if($lastSaveId === Pokemon::where('id', '=', $id)->first()->ownerSave()->first()->id) {
            return ['success' => false, 'error' => 'You cannot make an offer for a pokemon on the same save as the offered pokemon'];
        }

        $createdOfferPokemon = [];

        $offerPokemonId = OfferPokemon::query()->latest()->first()->id + 1;

        foreach ($offerIds as $offerId) {
            $offerPoke = new OfferPokemon();

            $offerPoke->id = $offerPokemonId;
            $offerPoke->pokemon_id = $offerId;

            if(!$offerPoke->save()) {
                // If any one of them fail to save, clean out old ones as the offer should be considered as invalid
                foreach ($createdOfferPokemon as $offerPokemon) {
                    $offerPokemon->delete();
                }

                return ['success' => false, 'error' => 'An unknown error occurred while trying to insert one of the offer pokemon'];
            }

            $createdOfferPokemon = $offerPoke;
        }

        $requestPokemonId = (OfferPokemon::query()->latest()->first()->id + 1);

        $requestPokemon = new OfferPokemon();
        $requestPokemon->id = $requestPokemonId;
        $requestPokemon->pokemon_id = $id;
        if(!$requestPokemon->save()) {
            // If the request pokemon entry could not be saved, the whole offer is invalid, clean up offer pokemon
            foreach ($createdOfferPokemon as $offerPokemon) {
                $offerPokemon->delete();
            }

            return ['success' => false, 'error' => 'An unknown error occurred while trying to insert one of the request pokemon'];
        }

        $offer = new Offer();

        $offer->offer_pokemon_id = $offerPokemonId;
        $offer->request_pokemon_id = $requestPokemonId;

        if(!$offer->save()) {
            foreach ($createdOfferPokemon as $offerPokemon) {
                $offerPokemon->delete();
            }

            $requestPokemon->delete();

            return ['success' => false, 'error' => 'An unknown error occurred while trying to insert the offer entry'];
        }

        return ['success' => true];
    }

    public function remove(Request $request, int $id)
    {
        $offer = Offer::where('id', '=', $id)->first();

        OfferPokemon::where('id', '=', $offer->offer_pokemon_id)->delete();
        OfferPokemon::where('id', '=', $offer->request_pokemon_id)->delete();

        $offer->delete();

        return ['success' => true];
    }
}
