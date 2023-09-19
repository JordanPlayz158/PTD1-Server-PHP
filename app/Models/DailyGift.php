<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DailyGift
 *
 * @property int $id
 * @property string $button
 * @property int $prize
 * @property int $cost
 * @property string $percentage
 * @method static \Illuminate\Database\Eloquent\Builder|DailyGift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyGift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyGift query()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyGift whereButton($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyGift whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyGift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyGift wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyGift wherePrize($value)
 * @mixin \Eloquent
 */
class DailyGift extends Model
{
    use HasFactory;
    protected $table = 'daily_gift';

}
