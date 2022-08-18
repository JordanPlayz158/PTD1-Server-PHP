<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 * @mixin \Eloquent
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|Save whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Poke[] $pokes
 * @property-read int|null $pokes_count
 */
class Save extends Model {
    /**
     * Get the ptd1 pokes for the save.
     */
    public function pokes() {
        return $this->hasMany(Poke::class);
    }

    ////public int $num;
    // called levelUnlocked (in SWF)
    public int $advanced = 0;
    public int $advanced_a = 0;
    // called HMP (in saveAccount Action)
    public int $p_numPoke = 0;
    // called HMI (in saveAccount Action)
    // Inventory Size
    public int $p_numItem = 0;
    // used for hacker check, number of shiny Pokémon you have (NOT SHADOW)
    public int $p_hs = 0;
    public ?string $nickname = null;
    public int $badges = 0;
    public string $avatar = 'none';
    // called haveFlash (in SWF), assuming it's talking about the Flash TM
    public int $classic = 0;
    // split by '|' and called extraInfo
    public string $classic_a = '';
    // called clevelCompleted (in SWF)
    public int $challenge = 0;
    public int $money = 50;
    public int $npcTrade = 0;
    public int $shinyHunt = 0;
    public int $version = 2;
    public array $pokes = array();
    public array $items = array();

    public function parse(array $save) {
        $this->num = $save['num'];
        $this->advanced = $save['advanced'];
        $this->advanced_a = $save['advanced_a'];
        $this->nickname = $save['nickname'];
        $this->badges = $save['badges'];
        $this->avatar = $save['avatar'];
        $this->classic = $save['classic'];
        $this->classic_a = $save['classic_a'];
        $this->challenge = $save['challenge'];
        $this->money = $save['money'];
        $this->npcTrade = $save['npcTrade'];
        $this->shinyHunt = $save['shinyHunt'];
        $this->version = $save['version'];

        $this->items = unserialize($save['items']);
    }
}
