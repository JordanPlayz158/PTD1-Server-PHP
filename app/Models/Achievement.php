<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Achievement
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $one
 * @property int|null $two
 * @property int|null $three
 * @property int|null $four
 * @property int|null $five
 * @property int|null $six
 * @property int|null $seven
 * @property int|null $eight
 * @property int|null $nine
 * @property int|null $ten
 * @property int|null $eleven
 * @property int|null $twelve
 * @property int|null $thirteen
 * @property int|null $fourteen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereEight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereEleven($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereFive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereFourteen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereNine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereOne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereSeven($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereSix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereTen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereThirteen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereThree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereTwelve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereTwo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereUserId($value)
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\AchievementFactory factory(...$parameters)
 * @mixin \Eloquent
 */
class Achievement extends Model
{
    use HasFactory;

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
