<?php

namespace App\View\Components\Pokemon;

use App\Models\GameCornerPokemon;
use Illuminate\View\Component;

class GameCorner extends Component
{
    public int $id;
    public int $cost;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $id, string $cost)
    {
        $this->id = intval($id);
        $this->cost = intval($cost);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.pokemon.gamecorner', ['id' => $this->id, 'cost' => $this->cost]);
    }
}
