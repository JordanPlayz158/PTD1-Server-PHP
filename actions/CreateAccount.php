<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/php/newPoke8.php');

class CreateAccount {
    function __construct($accounts, $post_data) {
        if(Utils::getAccount($accounts, $post_data) != null) {
            Utils::response("Result", "Failure");
            Utils::response("Reason", "taken");

            echo Utils::getResponse();
            exit;
        }

        $account = new Account();
        $account -> Email = $post_data['Email'];
        $account -> Pass = $post_data['Pass'];
        $account -> TrainerID = $trainerID = Utils::generateValidTrainerID($accounts);
        $account -> ProfileID = Utils::generateValidProfileID(10000000000000, $trainerID);

        for($i = 1; $i <= 3; $i++) {
            $account -> save[] = new Save();
        }

        $accounts[] = $account;
        newPoke8::$accounts = $accounts;

        Utils::saveData($accounts);
    }
}
?>