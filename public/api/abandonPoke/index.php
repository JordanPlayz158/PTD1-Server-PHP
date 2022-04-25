<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../RedisCache.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!isset($_POST['id'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Pokemon id must be supplied'
        ]);
        return;
    }

    if(!isset($_POST['save'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Save number must be supplied'
        ]);
        return;
    }

    $id = $_POST['id'];
    $saveNum = $_POST['save'];

    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');
    $email = getEmail($config);

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    $stmt = $conn->prepare("DELETE FROM pokes WHERE email = ? AND num = ? AND id = ?;");
    $stmt->bind_param('sii', $email, $saveNum, $id);
    $stmt->execute();

    echo json_encode(['success' => ($stmt->affected_rows === 1)]);

    $stmt->close();
} else {
    http_response_code(405);
}