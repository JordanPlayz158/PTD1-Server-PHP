<?php
class Poke {
    public string $reason;
    public int $num;
    public string $nickname;
    public int $exp;
    public int $lvl;
    public int $m1;
    public int $m2;
    public int $m3;
    public int $m4;
    public int $ability;
    // called moveSelected (in SWF), probably referring to which of the moves (m1, m2, m3, m4) was selected
    public int $mSel;
    public int $targetType;
    public string $tag;
    public string $item;
    public string $owner;
    public string $myID;
    public int $pos;
    public int $shiny = 0;

    public function parse(array $poke) {
        $this->num = $poke['pNum'];
        $this->nickname = $poke['nickname'];
        $this->exp = $poke['exp'];
        $this->lvl = $poke['lvl'];
        $this->m1 = $poke['m1'];
        $this->m2 = $poke['m2'];
        $this->m3 = $poke['m3'];
        $this->m4 = $poke['m4'];
        $this->ability = $poke['ability'];
        $this->mSel = $poke['mSel'];
        $this->targetType = $poke['targetType'];
        $this->tag = $poke['tag'];
        $this->item = $poke['item'];
        $this->owner = $poke['owner'];
        $this->myID = $poke['id'];
        $this->pos = $poke['pos'];
        $this->shiny = $poke['shiny'];
    }
}