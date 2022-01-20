<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../RedisCache.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php');

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(session_start()) {
        if(!isset($_SESSION['account_token'])) {
            exit("\"account_token\" not set in session, please login at \"/games/ptd/login.html\"");
        }
    } else {
        exit("Couldn't start session");
    }

    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    $redis = new RedisCache($config);
    $email = $redis->conn->get($_SESSION['account_token']);

    if(isset($_GET['save'])) {
        $num = $_GET['save'];

        $saves = getIndividualSave($conn, 'saves', $email, $num);
    } else {
        $savesMysql = getAccountDataByEmail($conn, 'saves', $email);
        $saves = array();

        foreach ($savesMysql as $saveArray) {
            $save = new Save();
            $save->parse($saveArray);

            $saves[] = $save;
        }
    }

    if (!isset($_GET['exclude']) || $_GET['exclude'] !== 'pokes') {
        if(isset($num)) {
            $saves[0]['pokes'] = getIndividualSave($conn, 'pokes', $email, $num);
        } else {
            $pokes = getAccountDataByEmail($conn, 'pokes', $email);

            // Pokemon
            foreach ($pokes as $pokeArray) {
                $poke = new Poke();
                $poke->parse($pokeArray);

                $saves[$pokeArray['num']]->pokes[] = $poke;
            }
        }
    }

    echo json_encode($saves);
} else {
    echo "Invalid Request Method! Accepted Method(s): GET";
}

function getIndividualSave(mysqli $conn, string $table, string $email, int $num) : ?array {
    $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ? AND num = ?");

    if(!$stmt) {
        echo $conn->errno . " " . $conn->error;
        return null;
    }

    $bind = $stmt->bind_param('si', $email, $num);

    if(!$bind) {
        echo $conn->errno . " " . $conn->error;
        return null;
    }

    $execute = $stmt->execute();

    if(!$execute) {
        echo $conn->errno . " " . $conn->error;
        return null;
    }

    $meta = $stmt->result_metadata();
    while ($field = $meta->fetch_field()) {
        $params[] = &$row[$field->name];
    }

    $bind = call_user_func_array(array($stmt, 'bind_result'), $params);

    if(!$bind) {
        echo $conn->errno . " " . $conn->error;
        return null;
    }

    $result = null;

    while ($stmt->fetch()) {
        foreach ($row as $key => $val) {
            $c[$key] = $val;
        }
        $result[] = $c;
    }

    $stmt->close();

    return $result;
}
