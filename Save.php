<?php
class Save {
    // called levelUnlocked (in SWF)
    public $Advanced = 0;
    public $Advanced_a = 0;
    // called HMP (in saveAccount Action)
    public $p_numPoke = 0;
    // called HMI (in saveAccount Action)
    // Inventory Size
    public $p_numItem = 0;
    // used for hacker check, number of shiny pokemon you have (NOT SHADOW)
    public $p_hs = 0;
    public $Nickname = "NA";
    public $Badges = 0;
    public $avatar = "none";
    // called haveFlash (in SWF), assuming it's talking about the Flash TM
    public $Classic = 0;
    // split by "|" and called extraInfo
    public $Classic_a = "";
    // called clevelCompleted (in SWF)
    public $Challenge = 0;
    public $Money = 50;
    public $NPCTrade = 0;
    public $ShinyHunt = 0;
    public $Version = 2;
    public $poke = array();
    public $items = array();
}