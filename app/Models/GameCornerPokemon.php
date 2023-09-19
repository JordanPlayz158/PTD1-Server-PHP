<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GameCornerPokemon
 *
 * @property int $id
 * @property int $pNum
 * @property string $nickname
 * @property int $exp
 * @property int $lvl
 * @property int $m1
 * @property int $m2
 * @property int $m3
 * @property int $m4
 * @property int $ability
 * @property int $mSel
 * @property int $targetType
 * @property int $shiny
 * @property int $cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon query()
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereAbility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereExp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereLvl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereM1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereM2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereM3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereM4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereMSel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon wherePNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereShiny($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereTargetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameCornerPokemon whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GameCornerPokemon extends Model
{
    use HasFactory;
    protected $table = 'game_corner_pokemon';
    protected $fillable = [
        'pNum',
        'nickname',
        'exp',
        'lvl',
        'm1',
        'm2',
        'm3',
        'm4',
        'ability',
        'mSel',
        'targetType',
        'shiny'
    ];

}
