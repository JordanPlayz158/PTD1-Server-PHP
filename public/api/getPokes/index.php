<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');
    $email = getEmail($config);

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    echo json_encode(getPokes($conn, $email));
} else {
    http_response_code(405);
}