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
    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    $pokesMySQL = $conn->query('SELECT *
       FROM pokes poke
       WHERE EXISTS (SELECT *
                                FROM trades trade
                                WHERE trade.email = poke.email
                                      AND trade.num = poke.num
                                      AND trade.id = poke.id);')->fetch_all(MYSQLI_ASSOC);
    $conn->close();

    $pokes = array();

    foreach ($pokesMySQL as $poke) {
        $pokemon = new Poke();
        $pokemon->parse($poke);
        //$pokemon->email = $poke['email'];
        $pokes[] = $pokemon;
    }

    echo json_encode($pokes);
} else {
    http_response_code(405);
}