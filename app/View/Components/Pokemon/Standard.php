<?php

namespace App\View\Components\Pokemon;

use Illuminate\View\Component;

class Standard extends Component
{
    public int $id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $id)
    {
        $this->id = intval($id);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.pokemon.standard', ['id' => $this->id]);
    }
}
