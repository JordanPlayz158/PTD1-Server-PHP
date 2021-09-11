<?php
require_once('../../Account.php');
require_once('../../Save.php');
require_once('../../Poke.php');
require_once('../../Item.php');
require_once('../../SaveAccount.php');

$post_data = array();

$body = file_get_contents('php://input');
$body = urldecode($body);
$body = str_replace("saveString=", "", $body);

foreach(explode("&", $body) as $urlVariable) {
    $keyAndValue = explode("=", $urlVariable);

    $post_data[$keyAndValue[0]] = $keyAndValue[1];
}

$accountsFile = "../../accounts.json";

if(!file_exists($accountsFile) || strlen(file_get_contents($accountsFile)) < 2) {
    $file = fopen($accountsFile, 'w');
    fwrite($file, "[]");
    fclose($file);
}

$accounts = array();
$response = "";

// Save the account credentials to json
$accountsJson = json_decode(file_get_contents($accountsFile), true);

foreach($accountsJson as $accountJson) {
    $account = new Account();

    $account -> Email = $accountJson['Email'];
    $account -> Pass = $accountJson['Pass'];
    $account -> CurrentSave = $accountJson['CurrentSave'];
    $account -> TrainerID = $accountJson['TrainerID'];
    $account -> ProfileID = $accountJson['ProfileID'];

    foreach($accountJson['save'] as $saveJson) {
        $save = new Save();

        $aStory = "a_story";
        $cStory = "c_story";

        $save -> Advanced = $saveJson['Advanced'];
        $save -> Advanced_a = $saveJson['Advanced_a'];
        $save -> p_numPoke = $saveJson['p_numPoke'];
        $save -> Nickname = $saveJson['Nickname'];
        $save -> Badges = $saveJson['Badges'];
        $save -> avatar = $saveJson['avatar'];
        $save -> Classic = $saveJson['Classic'];
        $save -> Classic_a = $saveJson['Classic_a'];
        $save -> Challenge = $saveJson['Challenge'];
        $save -> Money = $saveJson['Money'];
        $save -> NPCTrade = $saveJson['NPCTrade'];
        $save -> ShinyHunt = $saveJson['ShinyHunt'];
        $save -> p_numItem = $saveJson['p_numItem'];
        $save -> Version = $saveJson['Version'];

        // Items
        foreach($saveJson['items'] as $itemJson) {
            $item = new Item();

            $item -> num = $itemJson['num'];

            $save -> items[] = $item;
        }
        
        foreach($saveJson['poke'] as $pokeJson) {
            $poke = new Poke();
            
            $poke -> reason = $pokeJson['reason'];
            $poke -> num = $pokeJson['num'];
            $poke -> nickname = $pokeJson['nickname'];
            $poke -> exp = $pokeJson['exp'];
            $poke -> lvl = $pokeJson['lvl'];
            $poke -> m1 = $pokeJson['m1'];
            $poke -> m2 = $pokeJson['m2'];
            $poke -> m3 = $pokeJson['m3'];
            $poke -> m4 = $pokeJson['m4'];
            $poke -> ability = $pokeJson['ability'];
            $poke -> mSel = $pokeJson['mSel'];
            $poke -> targetType = $pokeJson['targetType'];
            $poke -> tag = $pokeJson['tag'];
            $poke -> item = $pokeJson['item'];
            $poke -> owner = $pokeJson['owner'];
            $poke -> myID = $pokeJson['myID'];
            $poke -> pos = $pokeJson['pos'];
            $poke -> shiny = $pokeJson['shiny'];

            $save -> poke[] = $poke;
        }
        
        $save -> HMI = $saveJson['p_numItem'];
        $save -> p_hs = $saveJson['p_hs'];

        $account -> save[] = $save;
    }

    $account -> accNickname = $accountJson['accNickname'];
    $account -> dex1 = $accountJson['dex1'];
    $account -> dex1Shiny = $accountJson['dex1Shiny'];
    $account -> dex1Shadow = $accountJson['dex1Shadow'];

    $accounts[] = $account;
}

switch($post_data['Action']) {
    case "createAccount":
        if(getAccount() != null) {
            response("Result", "Failure");
            response("Reason", "taken");

            echo $response;
            return;
        }

        $trainerID = generateValidTrainerID();
        $profileID = generateValidProfileID(10000000000000, $trainerID);

        $account = new Account();
        $account -> Email = $post_data['Email'];
        $account -> Pass = $post_data['Pass'];
        $account -> TrainerID = $trainerID;
        $account -> ProfileID = $profileID;

        $save = new Save();
        $account -> save[] = $save;
        $account -> save[] = $save;
        $account -> save[] = $save;

        $accounts[] = $account;

        $accountsJson = preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', json_encode($accounts));

        $file = fopen($accountsFile, 'w');
        fwrite($file, $accountsJson);
        fclose($file);
    case "loadAccount":
        loadAccount();
        break;
    case "saveAccount":
        new SaveAccount();
        break;
}

function loadAccount() {
    global $post_data;
    global $response;

    // Save the account credentials to json
    $account = getAccount();

    if($account != null) {
        response("Result", "Success");
        response("Reason", "LoggedIn");
        response("CurrentSave", $account -> CurrentSave);
        response("newSave", $account -> CurrentSave);
        response("TrainerID", $account -> TrainerID);
        response("ProfileID", $account -> ProfileID);
        response("accNickname", $account -> accNickname);
        response("dex1", $account -> dex1);
        response("dex1Shiny", $account -> dex1Shiny);
        response("dex1Shadow", $account -> dex1Shadow);

        $saves = $account -> save;

        for($i = 0; $i < count($saves); $i++) {
            $s = $i + 1;
            $save = $saves[$i];

            response("Advanced" . $s, $save -> Advanced);
            response("Advanced" . $s . "_a", $save -> Advanced_a);
            response("p" . $s . "_numPoke", $save -> p_numPoke);
            response("Nickname" . $s, $save -> Nickname);
            response("Badges" . $s, $save -> Badges);
            response("avatar" . $s, $save -> avatar);
            response("Classic" . $s, $save -> Classic);
            response("Classic" . $s . "_a", $save -> Classic_a);
            response("Challenge" . $s, $save -> Challenge);
            response("Money" . $s, $save -> Money);
            response("NPCTrade" . $s, $save -> NPCTrade);
            response("shinyHunt" . $s, $save -> ShinyHunt);
            response("p" . $s . "_numItem", $save -> p_numItem);
            response("Version" . $s, $save -> Version);

            $ii = 1;
            $pokeNum = 'p' . $s . '_poke_' . $ii . "_";

            $pokes = $save -> poke;

            foreach($pokes as $poke) {
                response($pokeNum . "nickname", $poke -> nickname);
                response($pokeNum . "num", $poke -> num);
                response($pokeNum . "lvl", $poke -> lvl);
                response($pokeNum . "exp", $poke -> exp);
                response($pokeNum . "owner", $poke -> owner);
                response($pokeNum . "targetType", $poke -> targetType);
                response($pokeNum . "tag", $poke -> tag);
                response($pokeNum . "myID", $poke -> myID);
                response("newPokePos_" . $poke -> pos, $poke -> myID);
                response($pokeNum . "pos", $poke -> pos);
                //Shiny (boolean)
                response($pokeNum . "noWay", $poke -> shiny);
                //numMoves
                response($pokeNum . "m1", $poke -> m1);
                response($pokeNum . "m2", $poke -> m2);
                response($pokeNum . "m3", $poke -> m3);
                response($pokeNum . "m4", $poke -> m4);
                response($pokeNum . "mSel", $poke -> mSel);

                $ii++;
                $pokeNum = 'p' . $s . '_poke_' . $ii . "_";
            }

            // Items
            $ii = 1;

            foreach($save -> items as $item) {
                response("p" . $s . "_item_" . $ii . "_num", $item -> num);
                $ii++;
            }


            response("HMI" . $s, $save -> p_numItem);
            response("p" . $s . "_hs", $save -> p_hs);
        }

        echo $response;
    } else {
        response("Result", "Failure");
        response("Reason", "NotFound");

        echo $response;
    }
}

function saveData() {
    global $accountsFile;
    global $accounts;

    $file = fopen($accountsFile, 'w');
    fwrite($file, preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', json_encode($accounts)));
    fclose($file);
}

function response($key, $value) {
    global $response;

    if(strlen($response) == 0) {
        $response = $response . $key . "=" . $value;
        return;
    }

    $response = $response . "&" . $key . "=" . $value;
}

function generateValidTrainerID() : int {
    global $accounts;
    $temp = mt_rand(333, 99999);

    foreach($accounts as $account) {
        if($temp == $account -> TrainerID) {
            $temp = generateValidTrainerID();
            break;
        }
    }

    return $temp;
}

function getAccount() : mixed {
    global $accounts;
    global $post_data;

    foreach($accounts as $account) {
        if($account -> Email == $post_data['Email']) {
            if($account -> Pass == $post_data['Pass']) {
                return $account;
            }
        }
    }

    return null;
}

function getPokeByID($pokes, $id) : Poke {
    foreach($pokes as $poke) {
        if($poke -> myID == $id) {
            return $poke;
        }
    }

    return new Poke();
}

function generateValidProfileID($currentSave, $trainerID) : String {
    return exec("java16 -jar ../../PTD1-Keygen-1.0-SNAPSHOT.jar " . $currentSave . " " . $trainerID . " true");
}

function generateUniquePokeID($pokes) : int {
    $temp = mt_rand(1, 999999);

    foreach($pokes as $poke) {
        if($temp == $poke -> myID) {
            $temp = generateUniquePokeID();
            break;
        }
    }

    return $temp;
}

function setPokeData($poke, $postKey, $pokeVariable) {
    global $post_data;

    if(isset($post_data[$postKey])) {
        $poke -> $pokeVariable = $post_data[$postKey];
    }
}

?>