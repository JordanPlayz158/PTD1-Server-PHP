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

    if(!isset($_GET['save'])) {
        echo json_encode([
            'success' => false,
            'error' => '"save" query parameter not sent'
        ]);
    }

    $savesStmt = $conn->prepare('SELECT uuid
       FROM saves
       WHERE email = ? AND num = ?');
    $savesStmt->bind_param('si', $email, $_GET['save']);
    $savesStmt->execute();

    $requestSave = $savesStmt->get_result()->fetch_all()[0][0];

    $offersStmt = $conn->prepare('SELECT * FROM offers WHERE requestSave = ?');
    $offersStmt->bind_param('i', $requestSave);
    $offersStmt->execute();

    $savesStmt->close();

    $pokes = array();

    $offersResult = $offersStmt->get_result();
    while(($row = $offersResult->fetch_row()) != null) {
        $offerArray = array();
        foreach (explode(',', $row[2]) as $id) {
            $pokesStmt = $conn->prepare('SELECT *
       FROM pokes
       WHERE uuid = ?');
            $pokesStmt->bind_param('i', $id);
            $pokesStmt->execute();

            if($pokesStmt->num_rows !== 1) {
                $poke = new Poke();
                $poke->parse($pokesStmt->get_result()->fetch_assoc());
                $offerArray[] = $poke;
            }

            $pokesStmt->close();
        }

        $ratio = count($offerArray) . ':';

        $requestArray = array();
        foreach (explode(',', $row[4]) as $id) {
            $pokesStmt = $conn->prepare('SELECT *
       FROM pokes
       WHERE uuid = ?');
            $pokesStmt->bind_param('i', $id);
            $pokesStmt->execute();

            if($pokesStmt->num_rows !== 1) {
                $poke = new Poke();
                $poke->parse($pokesStmt->get_result()->fetch_assoc());
                $requestArray[] = $poke;
            }

            $pokesStmt->close();
        }

        $ratio .= count($requestArray);

        $pokes[] = $ratio;
        $pokes[] = $row['id'];
        $pokes = array_merge($pokes, $offerArray, $requestArray);
    }

    $offersStmt->close();
    $conn->close();

    echo json_encode($pokes);
} else {
    http_response_code(405);
}