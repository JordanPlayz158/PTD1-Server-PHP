<?php

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(session_start()) {
        if(!isset($_SESSION['account_token'])) {
            echo json_encode([
                'success' => false,
                'error' => '"account_token" not set in session, please login at "/games/ptd/login.html"'
            ]);
            return;
        }
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Couldn\'t start session'
        ]);
        return;
    }

    if (!$_POST) {
        echo json_encode([
            'success' => false,
            'error' => 'Postdata must be "x-www-form-urlencoded"'
        ]);
        return;
    } else if(!isset($_POST['nickname'])) {
        echo json_encode([
            'success' => false,
            'error' => '"nickname" not set in post data'
        ]);
        return;
    }

    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    $redis = new RedisCache($config);
    $email = $redis->getSession();
    $redis->close();

    $accountStmt = $conn->prepare('UPDATE accounts SET accNickname = ? WHERE email = ?');
    $accountStmt->bind_param('ss', $_POST['nickname'], $email);
    $accountStmt->execute();
    $accountStmt->close();
    $conn->close();

    echo json_encode([
        'success' => true
    ]);
} else {
    http_response_code(405);
}