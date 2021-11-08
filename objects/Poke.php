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
}