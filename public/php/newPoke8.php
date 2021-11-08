<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Item.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../actions/CreateAccount.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../actions/LoadAccount.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../actions/SaveAccount.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    Utils::setEmptyFileContents(Utils::getConfigFile(), Utils::getConfigFileDefault());
    Utils::$config = $config = json_decode(file_get_contents(Utils::getConfigFile()), true);

    $body = str_replace('saveString=', '', urldecode(file_get_contents('php://input')));
    $post_data = Utils::urlVariablesToArray($body);
    //$start = milliseconds();
    if($config['maintenance']) {
        Utils::response('Result', 'Failure');
        Utils::response('Reason', 'maintenance');
        echo Utils::getResponse();
        return;
    }
 
    Utils::$mysql = $mysql = new MySQL();
    $conn = $mysql->conn;

    $accounts = getAccountByEmail($conn, 'accounts', $post_data['Email']);
    
    if($post_data['Action'] === 'createAccount') {
        createAccount($post_data);
        
        echo Utils::getResponse();
        logMySQL($conn, $body);
        
        $conn->close();
        return;
    }

    $accounts1 = $accounts[0];

    if(count($accounts) === 0) {
        Utils::response('Result', 'Failure');
        Utils::response('Reason', 'NotFound');

        echo Utils::getResponse();
        return;
    } else if(count($accounts) >= 1 && $post_data['Pass'] !== $accounts1['pass']) {
        Utils::response('Result', 'Failure');
        Utils::response('Reason', 'taken');

        echo Utils::getResponse();
        return;
    }
    
    $account = new Account();
        
    $account -> email = $accounts1['id'];
    $account -> pass = $accounts1['pass'];
    $account -> trainerId = $accounts1['trainerId'];
    $account -> accNickname = $accounts1['accNickname'];
    $account -> dex1 = $accounts1['dex1'];
    $account -> dex1Shiny = $accounts1['dex1Shiny'];
    $account -> dex1Shadow = $accounts1['dex1Shadow'];
    
    $saves = getAccountByEmail($conn, 'saves', $post_data['Email'] . ',%');

    foreach($saves as $saveArray) {
        $save = new Save();
            
        $save -> num = explode(',', $saveArray['id'])[1];
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

        $account -> saves[] = $save;
    }

    $pokes = getAccountByEmail($conn, 'pokes', $post_data['Email'] . ',%');
    
    // Pokemon
    foreach($pokes as $pokeArray) {
        $poke = new Poke();
        
        $id = explode(',', $pokeArray['id']);

        $poke -> num = $pokeArray['num'];
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
        $poke -> myID = $id[2];
        $poke -> pos = $pokeArray['pos'];
        $poke -> shiny = $pokeArray['shiny'];

        $account -> saves[$id[1]] -> pokes[] = $poke;
        
    }

    $items = getAccountByEmail($conn, 'items', $post_data['Email'] . ',%');

    // Items
    foreach($items as $itemArray) {
        $id = explode(',', $itemArray['id']);

        $item -> num = $itemArray['num'];

        $account -> saves[$id[1]] -> items[] = $item;
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
    
    switch($post_data['Action']) {
        case 'loadAccount':
            loadAccount($account);
            break;
        case 'saveAccount':
            saveAccount($account, $post_data, $conn);
            break;
        default:
            return;
    }
    
    echo Utils::getResponse();
    logMySQL($conn, $body);

    $conn->close();
    
    //echo milliseconds() - $start;
} else {
    echo "Invalid Request Method";
}

function milliseconds() : int {
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
}

function getAccountByEmail(mysqli $conn, string $table, string $id) : array {
    $stmt = $conn->prepare("SELECT * FROM $table WHERE id LIKE ?");
    $stmt->bind_param('s', $id);

    $stmt->execute();

    $columns = array();

    $metaResults = $stmt->result_metadata();
    $fields = $metaResults->fetch_fields();
    $statementParams='';
    //build the bind_results statement dynamically so I can get the results in an array
    foreach($fields as $field){
        if(empty($statementParams)){
            $statementParams.="\$column['".$field->name."']";
        } else {
            $statementParams.=", \$column['".$field->name."']";
        }
    }
    $statment="\$stmt->bind_result($statementParams);";
    eval($statment);
    while($stmt->fetch()){
        $columns[] = $column;
        //Now the data is contained in the assoc array $column. Useful if you need to do a foreach, or
        //if your lazy and didn't want to write out each param to bind.
    }

    $stmt->close();

    return $columns;
}

function logMySQL($conn, $body) {
    $response = Utils::urlVariablesToArray(Utils::getResponse());
    $responseResult = 'Result=' . $response['Result'] . '&Reason=' . $response['Reason'];
    $ip = getallheaders()['X-Forwarded-For'];
    $time = time();

    $stmt = $conn->prepare('INSERT INTO logs VALUES (?, ?, ?, ?);');
    $stmt->bind_param('isss', $time, $ip, $body, $responseResult);
    $stmt->execute();
    $stmt->close();
}
