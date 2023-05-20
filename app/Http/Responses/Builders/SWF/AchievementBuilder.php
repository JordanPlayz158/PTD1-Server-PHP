<?php

namespace App\Http\Responses\Builders\SWF;

use App\Enums\Reason;

class AchievementBuilder extends SWFBuilder
{
    public static function new(): self
    {
        return new self();
    }

    public function setReason(string|Reason $reason): self
    {
        $this->Reason = $reason;
        return $this;
    }

    public function setAchievement(int $num, string $achievement): self
    {
        $key = 'Ach' . $num;

        $this->$key = $achievement;
        return $this;
    }

//    public function create(): Response
//    {
//        return parent::create();
//    }
}
