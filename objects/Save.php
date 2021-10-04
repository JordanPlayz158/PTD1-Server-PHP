<?php
class Save {
    public $num;
    // called levelUnlocked (in SWF)
    public $advanced = 0;
    public $advanced_a = 0;
    // called HMP (in saveAccount Action)
    public $p_numPoke = 0;
    // called HMI (in saveAccount Action)
    // Inventory Size
    public $p_numItem = 0;
    // used for hacker check, number of shiny pokemon you have (NOT SHADOW)
    public $p_hs = 0;
    public $nickname = 'N/A';
    public $badges = 0;
    public $avatar = 'none';
    // called haveFlash (in SWF), assuming it's talking about the Flash TM
    public $classic = 0;
    // split by '|' and called extraInfo
    public $classic_a = '';
    // called clevelCompleted (in SWF)
    public $challenge = 0;
    public $money = 50;
    public $npcTrade = 0;
    public $shinyHunt = 0;
    public $version = 2;
    public $pokes = array();
    public $items = array();
}