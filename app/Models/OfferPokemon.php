<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferPokemon extends Model
{
    public function pokemon(): BelongsTo {
        return $this->belongsTo(Pokemon::class);
    }
}
