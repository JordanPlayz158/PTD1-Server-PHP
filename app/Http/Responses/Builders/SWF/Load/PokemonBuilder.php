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

    public static function new($saveNum): self
    {
        $new = new self();
        $new->saveNum = $saveNum;

        return $new;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @param int $num
     */
    public function setNum(int $num): void
    {
        $this->num = $num;
    }

    /**
     * @param int $lvl
     */
    public function setLvl(int $lvl): void
    {
        $this->lvl = $lvl;
    }

    /**
     * @param int $exp
     */
    public function setExp(int $exp): void
    {
        $this->exp = $exp;
    }

    /**
     * @param string $owner
     */
    public function setOwner(string $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @param string $targetType
     */
    public function setTargetType(string $targetType): void
    {
        $this->targetType = $targetType;
    }

    /**
     * @param string $tag
     */
    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

    /**
     * @param int $myID
     */
    public function setMyID(int $myID): void
    {
        $this->myID = $myID;
    }

    /**
     * @param int $pos
     */
    public function setPos(int $pos): void
    {
        $this->pos = $pos;
    }

    /**
     * @param int $noWay
     */
    public function setNoWay(int $noWay): void
    {
        $this->noWay = $noWay;
    }

    /**
     * @param int $m1
     */
    public function setM1(int $m1): void
    {
        $this->m1 = $m1;
    }

    /**
     * @param int $m2
     */
    public function setM2(int $m2): void
    {
        $this->m2 = $m2;
    }

    /**
     * @param int $m3
     */
    public function setM3(int $m3): void
    {
        $this->m3 = $m3;
    }

    /**
     * @param int $m4
     */
    public function setM4(int $m4): void
    {
        $this->m4 = $m4;
    }

    /**
     * @param int $mSel
     */
    public function setMSel(int $mSel): void
    {
        $this->mSel = $mSel;
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
        $pokePrefix = 'p' . $this->saveNum . '_poke_' . $this->num . '_';

        foreach (get_object_vars($this) as $key => $value) {
            $newKey = $pokePrefix . $key;
            $this->$newKey = $value;
            unset($key);
        }
    }

    public function create()
    {
        $this->prepareVariableKeyNames();
        return get_object_vars($this);
    }
}
