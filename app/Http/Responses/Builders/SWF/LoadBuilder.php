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

    protected SaveBuilder $save1;
    protected SaveBuilder $save2;
    protected SaveBuilder $save3;

    public static function new(): self
    {
        $new = new self();
        $new->save1 = SaveBuilder::new(1);
        $new->save2 = SaveBuilder::new(2);
        $new->save3 = SaveBuilder::new(3);

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

    private function fillDex($dex): string
    {
        if($dex === null) {
            $dex = '0';
        }

        while (strlen($dex) < 151)
            $dex .= '0';

        return $dex;
    }

    /**
     * @param string $dex
     */
    public function setDex(string $dex): self
    {
        $this->dex1 = $dex;
        return $this;
    }

    /**
     * @param string $shinyDex
     */
    public function setShinyDex(string $shinyDex): self
    {
        $this->dex1Shiny = $shinyDex;
        return $this;
    }

    /**
     * @param string $shadowDex
     */
    public function setShadowDex(string $shadowDex): self
    {
        $this->dex1Shadow = $shadowDex;
        return $this;
    }

    /**
     * @return SaveBuilder
     */
    public function getSave1(): SaveBuilder
    {
        return $this->save1;
    }

    /**
     * @return SaveBuilder
     */
    public function getSave2(): SaveBuilder
    {
        return $this->save2;
    }

    /**
     * @return SaveBuilder
     */
    public function getSave3(): SaveBuilder
    {
        return $this->save3;
    }

    public function create(): Response
    {
        $this->ProfileID = $this->generateProfileId();
        $this->dex1 = $this->fillDex($this->dex1);
        $this->dex1Shiny = $this->fillDex($this->dex1Shiny);
        $this->dex1Shadow = $this->fillDex($this->dex1Shadow);

        $vars = get_object_vars($this);

        $vars = array_merge($vars, $this->save1->create(), $this->save2->create(), $this->save3->create());

        return response()->flash();
    }
}
