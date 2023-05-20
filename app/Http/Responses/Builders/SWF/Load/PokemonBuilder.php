<?php

namespace App\Http\Responses\Builders\SWF\Load;

class PokemonBuilder
{
    protected int $saveNum;
    protected int $pokeNum;

    protected string $nickname;
    protected int $num;
    protected int $lvl;
    protected int $exp;
    protected string $owner;
    protected string $targetType;
    protected string $tag;
    protected int $myID;
    protected int $pos;
    protected int $noWay;
    protected int $m1;
    protected int $m2;
    protected int $m3;
    protected int $m4;
    protected int $mSel;

    public static function new($saveNum, $pokeNum): self
    {
        $new = new self();
        $new->saveNum = $saveNum;
        $new->pokeNum = $pokeNum;

        return $new;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @param int $num
     */
    public function setNum(int $num): self
    {
        $this->num = $num;
        return $this;
    }

    /**
     * @param int $lvl
     */
    public function setLvl(int $lvl): self
    {
        $this->lvl = $lvl;
        return $this;
    }

    /**
     * @param int $exp
     */
    public function setExp(int $exp): self
    {
        $this->exp = $exp;
        return $this;
    }

    /**
     * @param string $owner
     */
    public function setOwner(string $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @param string $targetType
     */
    public function setTargetType(string $targetType): self
    {
        $this->targetType = $targetType;
        return $this;
    }

    /**
     * @param string $tag
     */
    public function setTag(string $tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @param int $myID
     */
    public function setMyID(int $myID): self
    {
        $this->myID = $myID;
        return $this;
    }

    /**
     * @param int $pos
     */
    public function setPos(int $pos): self
    {
        $this->pos = $pos;
        return $this;
    }

    /**
     * @param int $shiny
     */
    public function getShiny(): int
    {
        return $this->noWay;
    }

    /**
     * @param int $shiny
     */
    public function setShiny(int $shiny): self
    {
        $this->noWay = $shiny;
        return $this;
    }

    /**
     * @param int $m1
     */
    public function setM1(int $m1): self
    {
        $this->m1 = $m1;
        return $this;
    }

    /**
     * @param int $m2
     */
    public function setM2(int $m2): self
    {
        $this->m2 = $m2;
        return $this;
    }

    /**
     * @param int $m3
     */
    public function setM3(int $m3): self
    {
        $this->m3 = $m3;
        return $this;
    }

    /**
     * @param int $m4
     */
    public function setM4(int $m4): self
    {
        $this->m4 = $m4;
        return $this;
    }

    /**
     * @param int $mSel
     */
    public function setMSel(int $mSel): self
    {
        $this->mSel = $mSel;
        return $this;
    }

    /**
     * I use the variable names for the keys, so I will rename the variables to the dynamic key names
     * before returning the object on create (and this can and only will be called by create when
     * the builder is finalized
     *
     * @return void
     */
    private function prepareVariableKeyNames(): void
    {
        $pokePrefix = 'p' . $this->saveNum . '_poke_' . $this->pokeNum . '_';

        unset($this->saveNum);
        unset($this->pokeNum);

        foreach (get_object_vars($this) as $key => $value) {
            if($key === 'myID') {
                $posKey = 'newPokePos_' . $this->pos;
                $this->$posKey = $value;
            }

            $newKey = $pokePrefix . $key;
            $this->$newKey = $value;
            unset($this->$key);
        }
    }

    public function create()
    {
        $this->prepareVariableKeyNames();
        return get_object_vars($this);
    }
}
