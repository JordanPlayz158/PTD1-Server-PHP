<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../RedisCache.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($_POST['newPass'])) {
        echo json_encode([
            'success' => false,
            'error' => '"newPass" not sent in POST data',
            'errorCode' => 2
        ]);
        return;
    }

    $newPass = $_POST['newPass'];

    if(isset($_POST['confirmPass']) && ($_POST['confirmPass'] !== $newPass)) {
        echo json_encode([
            'success' => false,
            'error' => '"newPass" did not match "confirmPass"',
            'errorCode' => 3
        ]);
        return;
    }

    $pass = password_hash($newPass, PASSWORD_DEFAULT);

    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');

    $redis = new RedisCache($config);

    if(!isset($_POST['reset_token'])) {
        echo json_encode([
            'success' => false,
            'error' => '"reset_token" not sent in POST data',
            'errorCode' => 2
        ]);
        return;
    }

    $resetToken = $_POST['reset_token'];
    $email = $redis->getResetPassword($resetToken);
    $redis->close();

    if(!$email) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid "reset_token" in POST',
            'errorCode' => 1
        ]);
        return;
    }

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    $stmt = $conn->prepare('UPDATE accounts SET pass = ? WHERE email = ?');
    $stmt->bind_param('ss', $pass, $email);
    $stmt->execute() or $stmt->close() && $conn->close() && die(json_encode(['success' => false, 'error' => 'Unknown error while changing password']));
    $stmt->close();

    $redis->deleteResetPassword($resetToken);
    $redis->close();

    echo json_encode([
        'success' => true
    ]);
}
