<?php
class Poke {
    public $reason;
    public $num;
    public $nickname;
    public $exp;
    public $lvl;
    public $m1;
    public $m2;
    public $m3;
    public $m4;
    public $ability;
    // called moveSelected (in SWF), probably referring to which of the moves (m1, m2, m3, m4) was selected
    public $mSel;
    public $targetType;
    public $tag;
    public $item;
    public $owner;
    public $myID;
    public $pos;
    public $shiny = 0;
}