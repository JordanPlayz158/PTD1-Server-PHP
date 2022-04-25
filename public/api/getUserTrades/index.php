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

    $stmt = $conn->prepare('SELECT *
       FROM pokes poke
       WHERE email = ? AND num = ? AND EXISTS (SELECT *
                                FROM trades trade
                                WHERE trade.email = poke.email
                                      AND trade.num = poke.num
                                      AND trade.id = poke.id);');
    $stmt->bind_param('si', $email, $_GET['save']);
    $stmt->execute();

    $pokes = array();
    $pokesResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    foreach ($pokesResult as $pokeResult) {
        $poke = new Poke();
        $poke->parse($pokeResult);
        $pokes[] = $poke;
    }

    $stmt->close();

    echo json_encode($pokes);
} else {
    http_response_code(405);
}