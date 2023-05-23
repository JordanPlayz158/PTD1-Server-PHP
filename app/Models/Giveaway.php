<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Giveaway
 *
 * @property int $id
 * @property int $owner_save_id
 * @property int $giveaway_pokemon_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $complete_at
 * @property-read \App\Models\Save $owner
 * @property-read int|null $participants_count
 * @method static \Illuminate\Database\Eloquent\Builder|Giveaway newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Giveaway newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Giveaway query()
 * @method static \Illuminate\Database\Eloquent\Builder|Giveaway whereCompleteAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Giveaway whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Giveaway whereGiveawayPokemonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Giveaway whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Giveaway whereOwnerSaveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Giveaway whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GiveawayEntry> $participants
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GiveawayPokemon> $pokemon
 * @property-read int|null $pokemon_count
 * @property int|null $completed
 * @method static \Illuminate\Database\Eloquent\Builder|Giveaway whereCompleted($value)
 * @property int $winner_save_id
 * @method static \Illuminate\Database\Eloquent\Builder|Giveaway whereWinnerSaveId($value)
 * @mixin \Eloquent
 */
class Giveaway extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_save_id',
        'giveaway_pokemon_id',
        'complete_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'complete_at' => 'datetime'
    ];

    public function pokemon(): HasMany
    {
        return $this->hasMany(GiveawayPokemon::class, 'id', 'giveaway_pokemon_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(GiveawayEntry::class, 'giveaway_id', 'id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Save::class, 'owner_save_id', 'id');
    }
}
