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

        $accounts[] = ['Email' => $post_data['Email'],
                        'Pass' => $post_data['Pass'],
                        'TrainerID' => $trainerID,
                        'ProfileID' => $profileID,
                        'Advanced1' => "0",
                        'p1_numPoke' => "0",
                        'Nickname1' => "",
                        'Badges1' => "0",
                        'Advanced2' => "0",
                        'p2_numPoke' => "0",
                        'Nickname2' => "",
                        'Badges2' => "0",
                        'Advanced3' => "0",
                        'p3_numPoke' => "0",
                        'Nickname3' => "",
                        'Badges3' => "0"
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
            if($tempAccount['Email'] == $post_data['Email']) {
                if($tempAccount['Pass'] == $post_data['Pass']) {
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
    $temp = rand(333, 99999);

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

function getCurrentSave($accounts, $post_data) : int {
    foreach($accounts as $tempAccount) {
        if($tempAccount['user'] == $post_data['Email']) {
            if(isset($tempAccount['CurrentSave'])) {
                return $tempAccount['CurrentSave'];
            }

            return 10000000000000;
        }
    }
}

?>