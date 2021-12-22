<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../actions/CreateAccount.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../actions/LoadAccount.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../actions/SaveAccount.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');

if(isset($_POST['debug'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');
    //$start = milliseconds();

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    if($config['maintenance']) {
        response('Result', 'Failure');
        response('Reason', 'maintenance');
        echo getResponse();
        logMySQL($conn);
        return;
    }
    
    if($_POST['Action'] === 'createAccount') {
        createAccount($mysql);
        
        echo getResponse();
        logMySQL($conn);
        
        $conn->close();
        return;
    }

    $accounts = getAccountDataByEmail($conn, 'accounts', $_POST['Email']);

    if(count($accounts) === 0) {
        response('Result', 'Failure');
        response('Reason', 'NotFound');

        echo getResponse();
        return;
    } else if(count($accounts) >= 1 && $_POST['Pass'] !== $accounts[0]['pass']) {
        response('Result', 'Failure');
        response('Reason', 'taken');

        echo getResponse();
        return;
    }
    
    $account = new Account();
    $account->parse($accounts[0]);
    
    $saves = getAccountDataByEmail($conn, 'saves', $_POST['Email']);

    foreach($saves as $saveArray) {
        $save = new Save();
        $save->parse($saveArray);

        $account -> saves[] = $save;
    }

    $pokes = getAccountDataByEmail($conn, 'pokes', $_POST['Email']);
    
    // Pokemon
    foreach($pokes as $pokeArray) {
        $poke = new Poke();
        $poke->parse($pokeArray);

        $account -> saves[$pokeArray['num']] -> pokes[] = $poke;
    }

    for($i = 0; $i < count($account -> saves); $i++) {
        $save = $account -> saves[$i];
        $pokes = $save -> pokes;

        $counter = 0;

        foreach($pokes as $poke) {
            if($poke->shiny === 1) {
                $counter++;
            }
        }

        $save -> p_numPoke = count($pokes);
        $save -> p_hs = $counter;

        $save -> p_numItem = count($save -> items);

        $account -> saves[$i] = $save;
    }
    
    switch($_POST['Action']) {
        case 'loadAccount':
            loadAccount($account);
            break;
        case 'saveAccount':
            saveAccount($account, $mysql, urlVariablesToArray($_POST['saveString']));
            break;
        default:
            return;
    }
    
    echo getResponse();
    logMySQL($conn);

    $conn->close();
    
    //echo milliseconds() - $start;
} else {
    echo "Invalid Request Method: " . $_SERVER['REQUEST_METHOD'];
}

function milliseconds() : int {
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
}