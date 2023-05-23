<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Giveaway extends Component
{
    public ?int $id = null;
    public bool $personal;

    /**
     * Create a new component instance.
     */
    public function __construct(string $id, string $personal = 'false')
    {
        if (is_numeric($id)) {
            $this->id = intval($id);
        }

        $this->personal = filter_var($personal, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if($this->id === null) return 'Invalid ID';

        $relations = Collection::make(['owner', 'pokemon']);

        $giveaway = \App\Models\Giveaway::whereId($this->id)->with($relations->undot()->toArray())->get()->first();

        return view('components.giveaway', ['giveaway' => $giveaway, 'personal' => $this->personal]);
    }
}
