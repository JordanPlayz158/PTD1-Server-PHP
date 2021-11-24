<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');

function createAccount() {
    $account = new Account();
    $account->email = $_POST['Email'];
    $account->pass = $_POST['Pass'];

    for ($i = 1; $i <= 3; $i++)
        $account->saves[] = new Save();

    Utils::$mysql->createAccount($account);
    loadAccount($account);
}