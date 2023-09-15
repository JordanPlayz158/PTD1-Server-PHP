<?php

namespace App\View\Components\Pokemon;

use Illuminate\View\Component;

class Standard extends Component
{
    public int $id;
    public bool $isUpForTrade;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $id, string $isUpForTrade)
    {
        $this->id = intval($id);
        $this->isUpForTrade = boolval($isUpForTrade);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.pokemon.standard', ['id' => $this->id, 'isUpForTrade' => $this->isUpForTrade]);
    }
}
