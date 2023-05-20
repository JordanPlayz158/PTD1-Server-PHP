<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Offer
 *
 * @property int $id
 * @property int $offerSave
 * @property string $offerIds
 * @property int $requestSave
 * @property string $requestIds
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereOfferIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereOfferSave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereRequestIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereRequestSave($value)
 * @property int|null $offer_pokemon
 * @property int|null $request_pokemon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereOfferPokemon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereRequestPokemon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereUpdatedAt($value)
 * @property int|null $offer_pokemon_id
 * @property int|null $request_pokemon_id
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereOfferPokemonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereRequestPokemonId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OfferPokemon[] $offerPokemon
 * @property-read int|null $offer_pokemon_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OfferPokemon[] $requestPokemon
 * @property-read int|null $request_pokemon_count
 * @mixin \Eloquent
 */
class Offer extends Model
{
    public function offerPokemon(): HasMany
    {
        return $this->hasMany(OfferPokemon::class, 'id', 'offer_pokemon_id');
    }

    public function requestPokemon(): HasMany
    {
        return $this->hasMany(OfferPokemon::class, 'id', 'request_pokemon_id');
    }
}
