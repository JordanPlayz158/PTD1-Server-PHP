<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../RedisCache.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    if (!$_POST) {
        echo json_encode([
            'success' => false,
            'error' => 'Postdata must be "x-www-form-urlencoded"'
        ]);
        return;
    } else if (!isset($_POST['email'])) {
        echo json_encode([
            'success' => false,
            'error' => '"email" postdata parameter must be set'
        ]);
        return;
    } else if (!isset($_POST['pass'])) {
        echo json_encode([
            'success' => false,
            'error' => '\"pass\" postdata parameter must be set'
        ]);
        return;
    }

    $accounts = getAccountDataByEmail($conn, 'accounts', $_POST['email']);

    if ($accounts !== null && count($accounts) === 1) {
        $account = new Account();
        $account->parse($accounts[0]);

        if (password_verify($_POST['pass'], $account->pass)) {
            $redis = new RedisCache($config);

            $token = generatePass(64);
            $redis->setSession($token, $account->email);
            $redis->close();

            session_start();
            $_SESSION['account_token'] = $token;
            header('Location: /games/ptd/account.html');

            echo json_encode([
                'success' => true
            ]);
            return;
        }
    }

    echo json_encode([
        'success' => false,
        'error' => 'Incorrect credentials',
        'errorCode' => 1
    ]);
} else {
    http_response_code(405);
}