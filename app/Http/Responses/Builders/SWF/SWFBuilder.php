<?php

namespace App\Http\Responses\Builders\SWF;

use App\Enums\Reason;
use App\Enums\Result;
use Illuminate\Http\Response;

class SWFBuilder
{
    protected Result $Result;
    protected Reason|string $Reason;

    public static function new(): self
    {
        return new self();
    }

    /**
     * @param Result $result
     */
    public function setResult(Result $result): self
    {
        $this->Result = $result;
        return $this;
    }

    /**
     * @param Reason $reason
     */
    public function setReason(Reason $reason): self
    {
        $this->Reason = $reason;
        return $this;
    }

    public function create(): Response {
        return response()->flash(get_object_vars($this));
    }
}
