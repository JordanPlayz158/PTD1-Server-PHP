<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');

function createAccount(MySQL $mysql) {
    $account = new Account();
    $account->email = $_POST['Email'];
    $account->pass = $_POST['Pass'];

    /*
     * Don't make any saves on create account
     * and don't make an entry in the database
     * Make an entry when updating values (can check if newGame=yes to insert rows into saves)
     */
    for ($i = 1; $i <= 3; $i++)
        $account->saves[] = new Save();

    $mysql->createAccount($account);
    loadAccount($account);
}