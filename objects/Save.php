<?php
class Save {
    public int $num;
    // called levelUnlocked (in SWF)
    public int $advanced = 0;
    public int $advanced_a = 0;
    // called HMP (in saveAccount Action)
    public int $p_numPoke = 0;
    // called HMI (in saveAccount Action)
    // Inventory Size
    public int $p_numItem = 0;
    // used for hacker check, number of shiny pokemon you have (NOT SHADOW)
    public int $p_hs = 0;
    public string $nickname = 'N/A';
    public int $badges = 0;
    public string $avatar = 'none';
    // called haveFlash (in SWF), assuming it's talking about the Flash TM
    public int $classic = 0;
    // split by '|' and called extraInfo
    public string $classic_a = '';
    // called clevelCompleted (in SWF)
    public int $challenge = 0;
    public int $money = 50;
    public int $npcTrade = 0;
    public int $shinyHunt = 0;
    public int $version = 2;
    public array $pokes = array();
    public array $items = array();
}