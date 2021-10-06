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
    //$start = milliseconds();
    if($config['maintenance']) {
        Utils::response('Result', 'Failure');
        Utils::response('Reason', 'maintenance');
        echo Utils::getResponse();
        return;
    }
 
    Utils::$mysql = $mysql = new MySQL();
    $conn = $mysql->conn;
    
    $post_data = array();
    
    $body = file_get_contents('php://input');
    $body = urldecode($body);
    $body = str_replace('saveString=', '', $body);
    Utils::$body = $body;
    
    foreach(explode('&', $body) as $urlVariable) {
        $keyAndValue = explode('=', $urlVariable);
        
        $post_data[$keyAndValue[0]] = $keyAndValue[1];
    }

    $accounts = getAccountByEmail($conn, 'accounts');
    
    if($post_data['Action'] === 'createAccount') {
        CreateAccount($post_data);
        
        echo Utils::getResponse();
        Utils::log();
        
        $conn->close();
        return;
    }

    if(count($accounts) === 0) {
        Utils::response('Result', 'Failure');
        Utils::response('Reason', 'NotFound');

        echo Utils::getResponse();
        return;
    } else if(count($accounts) >= 1 && $post_data['Pass'] !== $accounts['pass']) {
        Utils::response('Result', 'Failure');
        Utils::response('Reason', 'taken');

        echo Utils::getResponse();
        return;
    }
    
    $account = new Account();
        
    $account -> email = $accounts['email'];
    $account -> pass = $accounts['pass'];
    $account -> trainerId = $accounts['trainerId'];
    $account -> accNickname = $accounts['accNickname'];
    $account -> dex1 = $accounts['dex1'];
    $account -> dex1Shiny = $accounts['dex1Shiny'];
    $account -> dex1Shadow = $accounts['dex1Shadow'];
    
    $saves = getAccountByEmail($conn, 'saves');

    //print_r($saves);

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

    $pokes = getAccountByEmail($conn, 'pokes');
    
    // Pokemon
    foreach($pokes as $pokeArray) {
        print_r($pokes);
        
        $poke = new Poke();
        
        $id = explode(',', $pokeArray['id']);

        $poke -> id = $id['id'];       
        $poke -> reason = $pokeArray['reason'];
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
        $poke -> myID = $pokeArray['myID'];
        $poke -> pos = $pokeArray['pos'];
        $poke -> shiny = $pokeArray['shiny'];

        $account -> saves[$id[1]] -> pokes[] = $poke;
        
    }

    $items = getAccountByEmail($conn, 'items');

    // Items
    foreach($items as $itemArray) {
        $item = new Item();
        
        $id = explode(',', $itemArray['id']);
                
        $item -> id = $id[2];
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

    print_r($account);
    
    switch($post_data['Action']) {
        case 'loadAccount':
            LoadAccount($account);
            break;
        case 'saveAccount':
            SaveAccount($account, $post_data, $conn);
            break;
        default:
            return;
    }
    
    echo Utils::getResponse();
    Utils::log();

    $conn->close();
    
    //echo milliseconds() - $start;
}

function milliseconds() : int {
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
}

function getAccountByEmail(mysqli $conn, string $table) : array {
    global $post_data;

    if($table === 'accounts') {
        $stmt = $conn->prepare('SELECT * FROM ' . $table  . ' WHERE email = ?');
        $stmt->bind_param('s', $post_data['Email']);
    } else {
        $email = $post_data['Email'] . ',%';
    
        $stmt = $conn->prepare('SELECT * FROM ' . $table  . ' WHERE id LIKE ?');
        $stmt->bind_param('s', $email);
    }

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
        if($table !== "accounts") {
            $columns[] = $column;
        }
        //Now the data is contained in the assoc array $column. Useful if you need to do a foreach, or
        //if your lazy and didn't want to write out each param to bind.
    }

    $stmt->close();

    return count($columns) !== 0 ? $columns : $column;
}
?>