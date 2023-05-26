<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\GiveawayPokemon
 *
 * @property int $id
 * @property int $pokemon_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pokemon|null $pokemon
 * @method static \Illuminate\Database\Eloquent\Builder|GiveawayPokemon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GiveawayPokemon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GiveawayPokemon query()
 * @method static \Illuminate\Database\Eloquent\Builder|GiveawayPokemon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiveawayPokemon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiveawayPokemon wherePokemonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiveawayPokemon whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GiveawayPokemon extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'pokemon_id'
    ];

    public function pokemon(): BelongsTo
    {
        return $this->belongsTo(Pokemon::class);
    }
}
