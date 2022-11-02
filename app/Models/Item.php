<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Item
 *
 * @property int $save_id
 * @property int $item
 * @method static \Illuminate\Database\Eloquent\Builder|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereSaveId($value)
 * @mixin \Eloquent
 */
class Item extends Model
{
    protected $table = 'save_items';
}
