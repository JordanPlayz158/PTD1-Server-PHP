<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(!isset($_GET['id'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Pokemon id must be supplied'
        ]);
        return;
    }
    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');
    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    $stmt = $conn->prepare("SELECT * FROM pokes WHERE uuid = ?;");
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();

    $pokemon = new Poke();
    $pokemon->parse($stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]);

    echo json_encode($pokemon);
} else {
    http_response_code(405);
}