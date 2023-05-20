<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Pokemon
 *
 * @property int $id
 * @property int $save_id
 * @property string $email
 * @property int $num
 * @property int $pId
 * @property int|null $pNum
 * @property string|null $nickname
 * @property int|null $exp
 * @property int|null $lvl
 * @property int|null $m1
 * @property int|null $m2
 * @property int|null $m3
 * @property int|null $m4
 * @property int|null $ability
 * @property int|null $mSel
 * @property int|null $targetType
 * @property string|null $tag
 * @property string|null $item
 * @property string|null $owner
 * @property int|null $pos
 * @property int|null $shiny
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereAbility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereExp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereLvl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereM1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereM2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereM3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereM4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereMSel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon wherePId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon wherePNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon wherePos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereSaveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereShiny($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereTargetType($value)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pokemon whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Offer[] $offers
 * @property-read int|null $offers_count
 * @property-read \App\Models\Save $ownerSave
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Offer[] $requests
 * @property-read int|null $requests_count
 * @property-read \App\Models\Trade|null $trade
 * @mixin \Eloquent
 */
class Pokemon extends Model {
    protected $fillable = [
        'save_id',
        'pId'
    ];

    public function offers(): HasManyThrough
    {
        return $this->hasManyThrough(
            Offer::class,
            OfferPokemon::class,
            'pokemon_id', // Foreign key on the offer_pokemon table...
            'offer_pokemon_id', // // Foreign key on the offers table...
            'id', // Local key on the pokemon table...
            'id' // Local key on the offer_pokemon table...
            );
    }

    public function requests(): HasManyThrough
    {
        return $this->hasManyThrough(
            Offer::class,
            OfferPokemon::class,
            'pokemon_id', // Foreign key on the offer_pokemon table... <---------------|
            'request_pokemon_id', // // Foreign key on the offers table... <---------|--------|
            'id', // Local key on the pokemon table... <-------------------------------|        |
            'id' // Local key on the offer_pokemon table... <------------------------------|
        );
    }

    public function trade() : HasOne {
        return $this->hasOne(Trade::class, 'poke_id');
    }

    public function isUpForTrade(): bool
    {
        return $this->trade()->exists();
    }

    public function ownerSave(): BelongsTo
    {
        return $this->belongsTo(Save::class, 'save_id', 'id');
    }

    /*public string $id;
    public string $reason;
    public int $num;
    public string $nickname;
    public int $exp;
    public int $lvl;
    public int $m1;
    public int $m2;
    public int $m3;
    public int $m4;
    public int $ability;
    // called moveSelected (in SWF), probably referring to which of the moves (m1, m2, m3, m4) was selected
    public int $mSel;
    public int $targetType;
    public string $tag;
    public string $item;
    public string $owner;
    public string $myID;
    public int $pos;
    public int $shiny = 0;

    public function parse(array $poke) {
        $this->id = $poke['uuid'];
        $this->num = $poke['pNum'];
        $this->nickname = $poke['nickname'];
        $this->exp = $poke['exp'];
        $this->lvl = $poke['lvl'];
        $this->m1 = $poke['m1'];
        $this->m2 = $poke['m2'];
        $this->m3 = $poke['m3'];
        $this->m4 = $poke['m4'];
        $this->ability = $poke['ability'];
        $this->mSel = $poke['mSel'];
        $this->targetType = $poke['targetType'];
        $this->tag = $poke['tag'];
        $this->item = $poke['item'];
        $this->owner = $poke['owner'];
        $this->myID = $poke['id'];
        $this->pos = $poke['pos'];
        $this->shiny = $poke['shiny'];
    }*/
}
