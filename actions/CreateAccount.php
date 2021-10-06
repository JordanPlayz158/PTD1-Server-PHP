<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');

function CreateAccount($post_data)
{
    $trainerIDs = Utils::$mysql->conn->query('SELECT trainerId FROM accounts');
    $trainerIDs = $trainerIDs->fetch_all();

    $account = new Account();
    $account->email = $post_data['Email'];
    $account->pass = $post_data['Pass'];
    $account->trainerId = Utils::generateValidTrainerID($trainerIDs);

    for ($i = 1; $i <= 3; $i++)
        $account->saves[] = new Save();

    Utils::$mysql->createAccount($account);
    LoadAccount($account);
}

?>