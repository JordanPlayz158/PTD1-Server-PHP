<?php

class Account {
    // Never sent in response
    public $Email;
    // Never sent in response
    public $Pass;
    // ONLY 1 (not per save)
    public $CurrentSave = 10000000000000;
    // myTID is TrainerID (saveAccount Action)
    // ONLY 1 (not per save)
    public $TrainerID;
    // myVID is ProfileID (saveAccount Action)
    // ONLY 1 (not per save)
    public $ProfileID;

    public $save = array();

    public $accNickname = "NA";
    public $dex1 = 0;
    public $dex1Shiny = 0;
    public $dex1Shadow = 0;
}
?>