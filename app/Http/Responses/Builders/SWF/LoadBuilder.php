<?php

namespace App\Http\Responses\Builders\SWF;

use Illuminate\Http\Response;
use \App\Http\Responses\Builders\SWF\Load\SaveBuilder;

class LoadBuilder extends SWFBuilder
{
    // Not using programming naming conventions so the variable name is inline with the response key
    protected int $CurrentSave = 10000000000000;
    protected int $newSave = 10000000000000;
    protected int $TrainerID = 333;
    protected string $ProfileID;
    protected string $accNickname;
    protected string $dex1;
    protected string $dex1Shiny;
    protected string $dex1Shadow;

    protected array $saves = [];

    public static function new(): self
    {
        $new = new self();

        $new->saves[] = SaveBuilder::new(1);
        $new->saves[] = SaveBuilder::new(2);
        $new->saves[] = SaveBuilder::new(3);

        return $new;
    }

    /**
     * @param int $currentSave
     */
    public function setCurrentSave(int $currentSave): self
    {
        $this->CurrentSave = $currentSave;
        return $this;
    }

    /**
     * @param int $newSave
     */
    public function setNewSave(int $newSave): self
    {
        $this->newSave = $newSave;
        return $this;
    }

    /**
     * @param int $trainerID
     */
    public function setTrainerID(int $trainerID): self
    {
        $this->TrainerID = $trainerID;
        return $this;
    }

    /**
     * @param string $accNickname
     */
    public function setAccNickname(string $accNickname): self
    {
        $this->accNickname = $accNickname;
        return $this;
    }

    private function generateProfileId(): string
    {
        /* this keygen is just for a hacker check, so I hardcoded the profileId
           if for whatever reason you want to have random trainer and profileIds then
           I'll leave the Keygen code in just in case (and as we made it look nice and perform well)
           for CurrentSave randomization, you can use something like random_int to generate a number
           to the required size which is 14 character
           //return generateProfileId(10000000000000, $trainerID);

           //generateProfileId(10000000000000, 333); results in the output below */
        return 'ikkg';
    }

    private function fillDex(string|null $dex): string
    {
        if($dex === null) {
            $dex = '0';
        }

        while (strlen($dex) < 151)
            $dex .= '0';

        return $dex;
    }

    /**
     * @param string|null $dex
     * @return LoadBuilder
     */
    public function setDex(string|null $dex): self
    {
        $this->dex1 = $dex ?? '0';
        return $this;
    }

    /**
     * @param string|null $shinyDex
     * @return LoadBuilder
     */
    public function setShinyDex(string|null $shinyDex): self
    {
        $this->dex1Shiny = $shinyDex ?? '0';
        return $this;
    }

    /**
     * @param string|null $shadowDex
     * @return LoadBuilder
     */
    public function setShadowDex(string|null $shadowDex): self
    {
        $this->dex1Shadow = $shadowDex ?? '0';
        return $this;
    }

    /**
     * There are 3 save slots
     * num can only be 0, 1, and 2
     * the SWF starts at 1 rather than 0
     * but that is not a problem as the save number
     * is stored inside the object at initialization
     *
     * NOTE: This object is passed by reference so modify it directly
     */
    public function &getSave(int $num): SaveBuilder|bool
    {
        if($num < 0 || $num > 2) {
            $false = false;
            return $false;
        }

        return $this->saves[$num];
    }

    public function create(): Response
    {
        $this->ProfileID = $this->generateProfileId();
        $this->dex1 = $this->fillDex($this->dex1);
        $this->dex1Shiny = $this->fillDex($this->dex1Shiny);
        $this->dex1Shadow = $this->fillDex($this->dex1Shadow);

        $saves = [];

        foreach ($this->saves as $save) {
            $saves = array_merge($saves, $save->create());
        }

        unset($this->saves);

        $vars = array_merge(get_object_vars($this), $saves);

        return response()->flash($vars);
    }
}
