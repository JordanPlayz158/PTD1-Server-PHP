<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../RedisCache.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php');

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    $redis = new RedisCache($config);

    session_start();
    $email = $redis->conn->get('sessions.' . $_SESSION['account_token']);

    $accounts = getAccountDataByEmail($conn, 'accounts', $email);

    if($accounts === null) {
        echo json_encode(array('error' => 'No account found'));
    } else if(count($accounts) === 1) {
        $account = new Account();
        $account->parse($accounts[0]);
        unset($account->pass);

        $saves = getAccountDataByEmail($conn, 'saves', $email);

        foreach ($saves as $saveArray) {
            $save = new Save();
            $save->parse($saveArray);

            $account->saves[] = $save;
        }

        $pokes = getAccountDataByEmail($conn, 'pokes', $email);

        // Pokemon
        foreach ($pokes as $pokeArray) {
            $poke = new Poke();
            $poke->parse($pokeArray);

            $account->saves[$pokeArray['num']]->pokes[] = $poke;
        }

        echo json_encode($account);
    }
} else {
    echo "Invalid Request Method! Accepted Method(s): GET";
}
