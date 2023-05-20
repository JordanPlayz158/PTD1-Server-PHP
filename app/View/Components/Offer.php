<?php

namespace App\View\Components;

use App\Enums\Components\Trading;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Offer extends Component
{
    public ?int $id = null;
    public ?Trading $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $id, string $type)
    {
        if (is_numeric($id)) {
            $this->id = intval($id);
        }

        $this->type = Trading::coerce($type);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if($this->id === null) return 'Invalid ID';
        if($this->type === null) return 'Invalid Type';

        $relations = Collection::make(['offerPokemon', 'requestPokemon', 'offerPokemon.pokemon', 'requestPokemon.pokemon']);

        $offer = \App\Models\Offer::where('id', '=', $this->id)->with($relations->undot()->toArray())->get()->first();

        return view('components.offer', ['trading' => $offer, 'type' => $this->type]);
    }
}
