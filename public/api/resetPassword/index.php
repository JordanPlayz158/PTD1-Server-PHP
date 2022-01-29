<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../RedisCache.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($_POST['newPass'])) {
        echo 2;
        return;
    }
    $pass = password_hash($_POST['newPass'], PASSWORD_DEFAULT);

    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');

    $redis = new RedisCache($config);
    $redisConn = $redis->conn;

    $email = $redisConn->get('resetPassword.' . $_POST['reset_token']);

    if(!$email) {
        echo 1;
        return;
    }

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    $stmt = $conn->prepare('UPDATE accounts SET pass = ? WHERE email = ?');
    $stmt->bind_param('ss', $pass, $email);
    $stmt->execute() or $stmt->close() && $conn->close() && die(0);
    $stmt->close();

    $redisConn->del('resetPassword.' . $_POST['reset_token']);

    echo 'success';
}
