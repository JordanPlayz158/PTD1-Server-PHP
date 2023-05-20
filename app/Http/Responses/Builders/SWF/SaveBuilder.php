<?php

namespace App\Http\Responses\Builders\SWF;

use Illuminate\Http\Response;

class SaveBuilder extends SWFBuilder
{
    protected int $newSave = 10000000000000;
    // There is a dynamic key `newPokePos_{$pos} with dynamic length
    // So it will not be defined but rather dynamically added by method call

    public static function new(): self
    {
        return new self();
    }

    /**
     * @param int $newSave
     */
    public function setNewSave(int $newSave): self
    {
        $this->newSave = $newSave;
        return $this;
    }

    public function addNewPokePosition(int $pos, int $id): self {
        $varName = "newPokePos_$pos";
        $this->$varName = $id;
        return $this;
    }

    public function create(): Response
    {
        unset($this->Reason);
        return parent::create();
    }
}
