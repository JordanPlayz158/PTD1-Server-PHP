<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\OfferPokemon
 *
 * @property int $id
 * @property int $pokemon_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pokemon $pokemon
 * @method static \Illuminate\Database\Eloquent\Builder|OfferPokemon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferPokemon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferPokemon query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferPokemon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferPokemon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferPokemon wherePokemonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferPokemon whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfferPokemon extends Model
{
    public function pokemon(): BelongsTo {
        return $this->belongsTo(Pokemon::class);
    }
}
