<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../RedisCache.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php');

if(isset($_GET['debug'])) {
    //echo phpinfo();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    if (!$_POST) {
        exit("Postdata must be \"x-www-form-urlencoded\"");
    } else if (!isset($_POST['email'])) {
        exit("\"email\" postdata parameter must be set");
    } else if (!isset($_POST['pass'])) {
        exit("\"pass\" postdata parameter must be set");
    }

    $accounts = getAccountDataByEmail($conn, 'accounts', $_POST['email']);

    if (count($accounts) === 1) {
        $account = new Account();
        $account->parse($accounts[0]);

        if (password_verify($_POST['pass'], $account->pass)) {
            $redis = new RedisCache($config);
            $redisConn = $redis->conn;

            $token = generatePass(64);
            $redisConn->set('sessions.' . $token, $account->email, 86400);

            session_start();
            $_SESSION['account_token'] = $token;
            header('Location: /games/ptd/account.html');

            return 'success';
        }
    }

    return 1;
} else if($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo "Invalid Request Method! Accepted Method(s): GET, POST";
}