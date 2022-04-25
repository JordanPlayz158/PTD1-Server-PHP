<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../RedisCache.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

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
    } else if(!isset($_POST['save'])) {
        echo json_encode([
            'success' => false,
            'error' => '"save" not set in post data'
        ]);
        return;
    } else if(!isset($_POST['id'])) {
        echo json_encode([
            'success' => false,
            'error' => '"id" not set in post data'
        ]);
        return;
    }

    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    $redis = new RedisCache($config);
    $email = $redis->getSession();
    $redis->close();

    $accountStmt = $conn->prepare('DELETE FROM trades WHERE email = ? AND num = ? AND id = ?');
    $accountStmt->bind_param('sii', $email, $_POST['save'], $_POST['id']);
    $accountStmt->execute();

    if($accountStmt->affected_rows === 0) {
        $accountStmt->close();
        echo json_encode(['success' => false, 'error' => 'Pokemon is not up for trade']);
        return;
    }

    $accountStmt->close();


    $findPoke = $conn->prepare('SELECT * FROM pokes WHERE email = ? AND num = ? AND id = ?');
    $findPoke->bind_param('sii', $email, $_POST['save'], $_POST['id']);
    $findPoke->execute();

    $pokeRow = $findPoke->get_result()->fetch_assoc();

    if($pokeRow === null) {
        $findPoke->close();
        echo json_encode(['success' => false, 'error' => 'Couldn\'t find pokemon']);
        return;
    }

    $pokeNum = (int) $pokeRow['pNum'];
    $pokeNickname = (int) $pokeRow['nickname'];

    $findPoke->close();

    $updatePoke = $conn->prepare('UPDATE pokes SET pNum = ?, nickname = ? WHERE email = ? AND num = ? AND id = ?');



    $evolveNum = match ($pokeNum) {
        // Kadabra => Alakazam
        64 => 65,
        // Machoke => Machamp
        67 => 68,
        // Graveler => Golem
        75 => 76,
        // Haunter => Gengar
        93 => 94,

        default => -1,
    };

    // This is very bad, I am tired so fix later
    $nickname = match ($evolveNum) {
        65 => 'Alakazam',
        68 => 'Machamp',
        76 => 'Golem',
        94 => 'Gengar',

        default => $pokeNickname
    };

    if($evolveNum != -1) {
        $updatePoke->bind_param('issii', $evolveNum, $nickname, $email, $_POST['save'], $_POST['id']);

        $updatePoke->execute();

        if($updatePoke->affected_rows === 0) {
            $updatePoke->close();
            echo json_encode(['success' => false, 'error' => 'Couldn\'t find pokemon']);
            return;
        }
    }

    $updatePoke->close();
    $conn->close();

    echo json_encode([
        'success' => true
    ]);
} else {
    http_response_code(405);
}