<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Save
 *
 * @property int $id
 * @property string $email
 * @property int $num
 * @property int|null $advanced
 * @property int|null $advanced_a
 * @property string|null $nickname
 * @property int|null $badges
 * @property string|null $avatar
 * @property int|null $classic
 * @property string|null $classic_a
 * @property int|null $challenge
 * @property int|null $money
 * @property int|null $npcTrade
 * @property int|null $shinyHunt
 * @property int|null $version
 * @property string|null $items
 * @method static \Illuminate\Database\Eloquent\Builder|Save newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Save newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Save query()
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereAdvanced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereAdvancedA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereBadges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereChallenge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereClassic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereClassicA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereNpcTrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereShinyHunt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereVersion($value)
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereUserId($value)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereUpdatedAt($value)
 * @property-read int|null $items_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Pokemon[] $pokemon
 * @property-read int|null $pokemon_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Pokemon[] $allPokemon
 * @property-read int|null $all_pokemon_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Pokemon[] $tradePokemon
 * @property-read int|null $trade_pokemon_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\SaveFactory factory(...$parameters)
 * @mixin \Eloquent
 */
class Save extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'num'
    ];

    /**
     * Get the ptd1 pokemon for the save.
     *
     * NOTE: This method returns all pokemon EXCEPT pokemon that are up for trade, if you need ALL pokemon
     * then use allPokemon() method
     */
    public function pokemon(): HasMany
    {
        return $this->hasMany(Pokemon::class)->whereNotExists(function (Builder $query) {
            // Where pokemon is NOT up for trade
            $query->from('trades')->whereColumn('poke_id', '=', 'pokemon.id');
        })->whereNotExists(function (Builder $query) {
            // Where pokemon is NOT up for giveaway
            $query->from('giveaway_pokemon')
                ->whereColumn('pokemon_id', '=', 'pokemon.id')
                ->join('giveaways', 'giveaways.id', '=', 'giveaway_pokemon.id')
                // AND Where giveaway has NOT completed
                ->where('giveaways.complete_at', '>', Carbon::now('UTC'));
        });
    }

    public function tradePokemon(): HasMany
    {
        return $this->hasMany(Pokemon::class)->whereExists(function (Builder $query) {
            $query->from('trades')->whereColumn('poke_id', '=', 'pokemon.id');
        });
    }

    public function allPokemon() : HasMany
    {
        return $this->hasMany(Pokemon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*////public int $num;
    // called HMP (in saveAccount Action)
    public int $p_numPoke = 0;
    // called HMI (in saveAccount Action)
    // Inventory Size
    public int $p_numItem = 0;
    // used for hacker check, number of shiny Pokémon you have (NOT SHADOW)
    public int $p_hs = 0;
    public array $pokes = array();
    public array $items = array();
    */
}
