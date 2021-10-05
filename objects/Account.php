<?php
class Account {
    // Never sent in response
    public $email;
    // Never sent in response
    public $pass;
    // myTID is TrainerID (saveAccount Action)
    // ONLY 1 (not per save)
    public $trainerId;

    public $saves = array();

    public $accNickname = 'N/A';
    public $dex1;
    public $dex1Shiny;
    public $dex1Shadow;
}
?>