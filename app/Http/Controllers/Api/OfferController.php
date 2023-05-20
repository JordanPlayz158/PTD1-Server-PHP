<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Web\ExcludeController;
use App\Models\Offer;
use App\Models\OfferPokemon;
use App\Models\Pokemon;
use App\Models\Save;
use Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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

        if(empty($offerIds)) {
            return ['success' => false, 'error' => 'You can\'t make an offer for pokemon while offering no pokemon'];
        }

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

        $lastId = Cache::get('lastOfferPokemonId');

        if($lastId === null) {
            $lastId = OfferPokemon::query()->latest()->first()->id;
        }

        $lastId++;
        $offerPokemonId = $lastId;

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

        $lastId++;
        $requestPokemonId = $lastId;

        Cache::set('lastOfferPokemonId', $lastId);

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

    public function accept(Request $request, int $id) {
        $offer = Offer::whereId($id)->first();

        $requestUserId = $request->user()->id;

        // ID to transfer offer pokemon to
        $requestSaveId = -1;
        $userOwnsPokemonBeingRequested = false;

        $offerPokemon = $offer->offerPokemon();
        $requestPokemon = $offer->requestPokemon();

        foreach($requestPokemon->lazy() as $pokemon) {
            // Only need to check the first request pokemon
            // due to the checks that need to be passed in order for the offer to be made in the first place

            $requestSave = $pokemon->pokemon()->first()->ownerSave()->first();

            // Also I wish it wasn't this messy
            if($requestSave->user()->first()->id === $requestUserId) {
                $userOwnsPokemonBeingRequested = true;
                $requestSaveId = $requestSave->id;
                break;
            }
        }

        if($userOwnsPokemonBeingRequested && $requestSaveId !== -1) {
            // ID to transfer request pokemon to
            $offerSaveId = $offerPokemon->first()->pokemon()->first()->ownerSave()->first()->id;

            $requestPokemon->each(function (OfferPokemon $pokemon) use ($offerSaveId) {
                $this->cleanOffers($offerSaveId, $pokemon->pokemon()->first());
            });

            $offerPokemon->each(function (OfferPokemon $pokemon) use ($requestSaveId) {
                $this->cleanOffers($requestSaveId, $pokemon->pokemon()->first());
            });

            return ['success' => true];
        }

        return ['success' => false];
    }

    private function getUniquePokemonId(HasMany $pokemon) {
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

    private function cleanOffers(int $save, $pokemon) {
        $offerPokemonEntries = OfferPokemon::where('pokemon_id', '=', $pokemon->id);

        $offerPokemonEntries->each(function (OfferPokemon $offerPokemonEntry) {
            $id = $offerPokemonEntry->id;

            Offer::where('offer_pokemon_id', '=', $id)
                ->orWhere('request_pokemon_id', '=', $id)->delete();
        });

        $pokemon->update(['save_id' => $save, 'pId' => $this->getUniquePokemonId(Save::whereId($save)->first()->pokemon())]);
        $pokemon->trade()->delete();
    }

    public function remove(Request $request, int $id)
    {
        $offer = Offer::where('id', '=', $id)->first();

        $userId = $request->user()->id;
        $userOwnsPokemonInOffer = false;

        $offerPokemon = $offer->offerPokemon();
        $requestPokemon = $offer->requestPokemon();

        foreach ($offerPokemon->lazy() as $pokemon) {
            if($pokemon->pokemon()->first()->ownerSave()->first()->user()->first()->id === $userId) {
                $userOwnsPokemonInOffer = true;
                break;
            }
        }

        // Is a bit wasteful but don't want to duplicate code
        // It is wasteful as lazyById needs to fetch, potentially, 1000 entries from the db
        // just to start the loop, and it may return instantly if the user owned the offer pokemon
        foreach ($requestPokemon->lazyById() as $pokemon) {
            if($userOwnsPokemonInOffer) break;

            if($pokemon->pokemon()->first()->ownerSave()->first()->user()->first()->id === $userId) {
                $userOwnsPokemonInOffer = true;
                break;
            }
        }

        if($userOwnsPokemonInOffer) {
            $offerPokemon->delete();
            $requestPokemon->delete();

            $offer->delete();

            return ['success' => true];
        }

        return ['success' => false];
    }
}
