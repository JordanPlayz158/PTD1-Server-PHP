<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\GiveawayEntry
 *
 * @property int $giveaway_id
 * @property int $save_id
 * @property-read \App\Models\Save|null $entrySave
 * @method static \Illuminate\Database\Eloquent\Builder|GiveawayEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GiveawayEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GiveawayEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder|GiveawayEntry whereGiveawayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiveawayEntry whereSaveId($value)
 * @mixin \Eloquent
 */
class GiveawayEntry extends Model {
    use HasFactory;

    protected $table = 'giveaway_entries';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'giveaway_id',
        'save_id'
    ];

    public function entrySave(): BelongsTo
    {
        return $this->belongsTo(Save::class, 'save_id', 'id');
    }
}
