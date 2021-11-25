<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../actions/CreateAccount.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../actions/LoadAccount.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../actions/SaveAccount.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    Utils::setEmptyFileContents(Utils::getConfigFile(), Utils::getConfigFileDefault());
    Utils::$config = $config = json_decode(file_get_contents(Utils::getConfigFile()), true);
    //$start = milliseconds();

    Utils::$mysql = $mysql = new MySQL();
    $conn = $mysql->conn;

    if($config['maintenance']) {
        Utils::response('Result', 'Failure');
        Utils::response('Reason', 'maintenance');
        echo Utils::getResponse();
        logMySQL($conn);
        return;
    }
    
    if($_POST['Action'] === 'createAccount') {
        createAccount();
        
        echo Utils::getResponse();
        logMySQL($conn);
        
        $conn->close();
        return;
    }

    $accounts = getAccountDataByEmail($conn, 'accounts', $_POST['Email']);
    $accounts1 = $accounts[0];

    if(count($accounts) === 0) {
        Utils::response('Result', 'Failure');
        Utils::response('Reason', 'NotFound');

        echo Utils::getResponse();
        return;
    } else if(count($accounts) >= 1 && $_POST['Pass'] !== $accounts1['pass']) {
        Utils::response('Result', 'Failure');
        Utils::response('Reason', 'taken');

        echo Utils::getResponse();
        return;
    }
    
    $account = new Account();
        
    $account -> email = $accounts1['email'];
    $account -> pass = $accounts1['pass'];
    $account -> accNickname = $accounts1['accNickname'];
    $account -> dex1 = $accounts1['dex1'];
    $account -> dex1Shiny = $accounts1['dex1Shiny'];
    $account -> dex1Shadow = $accounts1['dex1Shadow'];
    
    $saves = getAccountDataByEmail($conn, 'saves', $_POST['Email']);

    foreach($saves as $saveArray) {
        $save = new Save();
            
        $save -> num = $saveArray['num'];
        $save -> advanced = $saveArray['advanced'];
        $save -> advanced_a = $saveArray['advanced_a'];
        $save -> nickname = $saveArray['nickname'];
        $save -> badges = $saveArray['badges'];
        $save -> avatar = $saveArray['avatar'];
        $save -> classic = $saveArray['classic'];
        $save -> classic_a = $saveArray['classic_a'];
        $save -> challenge = $saveArray['challenge'];
        $save -> money = $saveArray['money'];
        $save -> npcTrade = $saveArray['npcTrade'];
        $save -> shinyHunt = $saveArray['shinyHunt'];
        $save -> version = $saveArray['version'];

        $items = unserialize($saveArray['items']);
        // Items
        foreach($items as $item) {
            $save -> items[] = $item;
        }

        $account -> saves[] = $save;
    }

    $pokes = getAccountDataByEmail($conn, 'pokes', $_POST['Email']);
    
    // Pokemon
    foreach($pokes as $pokeArray) {
        $poke = new Poke();

        $poke -> num = $pokeArray['pNum'];
        $poke -> nickname = $pokeArray['nickname'];
        $poke -> exp = $pokeArray['exp'];
        $poke -> lvl = $pokeArray['lvl'];
        $poke -> m1 = $pokeArray['m1'];
        $poke -> m2 = $pokeArray['m2'];
        $poke -> m3 = $pokeArray['m3'];
        $poke -> m4 = $pokeArray['m4'];
        $poke -> ability = $pokeArray['ability'];
        $poke -> mSel = $pokeArray['mSel'];
        $poke -> targetType = $pokeArray['targetType'];
        $poke -> tag = $pokeArray['tag'];
        $poke -> item = $pokeArray['item'];
        $poke -> owner = $pokeArray['owner'];
        $poke -> myID = $pokeArray['id'];
        $poke -> pos = $pokeArray['pos'];
        $poke -> shiny = $pokeArray['shiny'];

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
            saveAccount($account, $mysql, Utils::urlVariablesToArray($_POST['saveString']));
            break;
        default:
            return;
    }
    
    echo Utils::getResponse();
    logMySQL($conn);

    $conn->close();
    
    //echo milliseconds() - $start;
} else {
    echo "Invalid Request Method";
}

function milliseconds() : int {
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
}

function getAccountDataByEmail(mysqli $conn, string $table, string $email) : array {
    $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
    $stmt->bind_param('s', $email);

    $stmt->execute();

    $meta = $stmt->result_metadata();
    while ($field = $meta->fetch_field()) {
        $params[] = &$row[$field->name];
    }

    call_user_func_array(array($stmt, 'bind_result'), $params);

    $result = array();

    while ($stmt->fetch()) {
        foreach ($row as $key => $val) {
            $c[$key] = $val;
        }
        $result[] = $c;
    }

    $stmt->close();

    return $result;
}

function logMySQL($conn) {
    $response = Utils::urlVariablesToArray(Utils::getResponse());
    $responseResult = "Result={$response['Result']}&Reason={$response['Reason']}";
    $ip = getallheaders()['X-Forwarded-For'];
    $time = time();
    $body = file_get_contents('php://input');

    $stmt = $conn->prepare('INSERT INTO logs VALUES (?, ?, ?, ?);');
    $stmt->bind_param('isss', $time, $ip, $body, $responseResult);
    $stmt->execute();
    $stmt->close();
}
