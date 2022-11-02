<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 * @mixin \Eloquent
 * @property int|null $offer_pokemon
 * @property int|null $request_pokemon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereOfferPokemon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereRequestPokemon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereUpdatedAt($value)
 * @property int $pokemon_id
 * @method static \Illuminate\Database\Eloquent\Builder|OfferPokemon wherePokemonId($value)
 */
class OfferPokemon extends Model
{

}
