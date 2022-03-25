<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../RedisCache.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');
    $email = getEmail($config);

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    $accounts = getAccountDataByEmail($conn, 'accounts', $email);

    if($accounts === null) {
        echo json_encode([
            'success' => false,
            'error' => 'No account found'
        ]);
        return;
    } else if(count($accounts) === 1) {
        $account = new Account();
        $account->parse($accounts[0]);
        unset($account->pass);

        if (!isset($_GET['exclude']) || !in_array('saves' , explode(',', $_GET['exclude']))) {
            $saves = getSaves($conn, $email);

            foreach ($saves as $save) {
                $account->saves[] = $save;
            }
        }

        echo json_encode($account);
    }
} else {
    http_response_code(405);
}