<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Trade
 *
 * @property int $poke_id
 * @method static \Illuminate\Database\Eloquent\Builder|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder|Item wherePokemonId($value)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade wherePokeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Trade extends Model
{

}
