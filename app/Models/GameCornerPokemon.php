<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
