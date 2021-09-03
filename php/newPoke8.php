<?php

$post_data = array();

foreach(explode("&", file_get_contents('php://input')) as $value) {
    $keyAndValue = explode("=", $value);

    $post_data[$keyAndValue[0]] = $keyAndValue[1];
}

$action = $post_data['Action'];

$accountsFile = "accounts.json";
if(!file_exists($accountsFile)) {
    $openedFile = fopen($accountsFile, 'w');
    fwrite($openedFile, "[]");
    fclose($openedFile);
}

switch($action) {
    case "createAccount":
        // Save the account credentials to json
        $accounts = json_decode(file_get_contents($accountsFile), true);

        $trainerID = generateValidTrainerID($accounts);
        $profileID = generateValidProfileID($accounts);

        $accounts[] = ['user' => $post_data['Email'],
                        'pass' => $post_data['Pass'],
                        'TrainerID' => $trainerID,
                        'ProfileID' => $profileID,
                        'Advanced1' => "1",
                        'p1_numPoke' => "1",
                        'Nickname1' => "Test",
                        'Badges1' => "1",
                        'Advanced2' => "0",
                        'p2_numPoke' => "0",
                        'Nickname2' => "Test2",
                        'Badges2' => "3",
                        'Advanced3' => "1",
                        'p3_numPoke' => "0",
                        'Nickname3' => "Test3",
                        'Badges3' => "5"
                    ];
        $accounts = json_encode($accounts);

        $openedFile = fopen($accountsFile, 'w');
        fwrite($openedFile, $accounts);
        fclose($openedFile);
    case "loadAccount":
        // Save the account credentials to json
        $accounts = json_decode(file_get_contents($accountsFile), true);

        $account = null;

        foreach($accounts as $tempAccount) {
            if($tempAccount['user'] == $post_data['Email']) {
                if($tempAccount['pass'] == $post_data['Pass']) {
                    $account = $tempAccount;
                    break;
                }
            }
        }

        if($account != null) {
            echo "Result=Success" .
            "&Reason=LoggedIn" .
            "&CurrentSave=" . getCurrentSave($accounts, $post_data) .
            "&TrainerID=" . $account['TrainerID'] .
            "&ProfileID=" . $account['ProfileID'] .
            "&Advanced1=" . $account['Advanced1'] .
            "&p1_numPoke=" . $account['p1_numPoke'] .
            "&Nickname1=" . $account['Nickname1'] .
            "&Version1=750" .
            "&Advanced2=" . $account['Advanced2'] .
            "&p2_numPoke=" . $account['p2_numPoke'] .
            "&Nickname2=" . $account['Nickname2'] .
            "&Version2=750" .
            "&Advanced3=" . $account['Advanced3'] .
            "&p3_numPoke=" . $account['p3_numPoke'] .
            "&Nickname3=" . $account['Nickname3'] .
            "&Version3=750";
        } else {
            echo "Username or Password are incorrect.";
        }
}

function generateValidTrainerID($accounts) : int {
    $temp = rand();

    foreach($accounts as $tempAccount) {
        if($temp == $tempAccount['TrainerID']) {
            $temp = generateValidTrainerID($accounts);
            break;
        }
    }

    return $temp;
}

function generateValidProfileID($accounts) : int {
    $temp = rand();

    foreach($accounts as $tempAccount) {
        if($temp == $tempAccount['ProfileID']) {
            $temp = generateValidProfileID($accounts);
            break;
        }
    }

    return $temp;
}

function getCurrentSave($accounts, $post_data) : String {
    foreach($accounts as $tempAccount) {
        if($tempAccount['user'] == $post_data['Email']) {
            if(isset($tempAccount['CurrentSave'])) {
                return $tempAccount['CurrentSave'];
            }

            return "NotFound";
        }
    }
}

?>