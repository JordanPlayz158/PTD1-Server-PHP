<?php
require_once('objects/Account.php');
require_once('objects/Poke.php');

class Utils {
    private static $response = "";

    public static function getAccount($accounts, $post_data) : mixed {
        foreach($accounts as $account) {
            if($account -> Email == $post_data['Email']) {
                if($account -> Pass == $post_data['Pass']) {
                    return $account;
                }
            }
        }
    
        return null;
    }

    public static function getPokeByID($pokes, $id) : Poke {
        foreach($pokes as $poke) {
            if($poke -> myID == $id) {
                return $poke;
            }
        }
    
        return new Poke();
    }

    public static function setPokeData($post_data, $poke, $postKey, $pokeVariable) {    
        if(isset($post_data[$postKey])) {
            $poke -> $pokeVariable = $post_data[$postKey];
        }
    }

    public static function saveData($accounts) {
        $file = fopen(Utils::getAccountsFile(), 'w');
        fwrite($file, preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', json_encode($accounts)));
        fclose($file);
    }

    public static function getResponse() : String {
        global $response;

        return $response;
    }
    
    public static function response($key, $value) {
        global $response;

        if(strlen($response) == 0) {
            $response = $response . $key . "=" . $value;
            return;
        }
    
        $response = $response . "&" . $key . "=" . $value;
    }
    
    public static function generateValidTrainerID($accounts) : int {
        $temp = mt_rand(333, 99999);
    
        foreach($accounts as $account) {
            if($temp == $account -> TrainerID) {
                $temp = generateValidTrainerID();
                break;
            }
        }
    
        return $temp;
    }
    
    public static function generateValidProfileID($currentSave, $trainerID) : String {
        return exec("java16 -jar ../../PTD1-Keygen-1.0-SNAPSHOT.jar " . $currentSave . " " . $trainerID . " true");
    }
    
    public static function generateUniquePokeID($pokes) : int {
        $temp = mt_rand(1, 999999);
    
        foreach($pokes as $poke) {
            if($temp == $poke -> myID) {
                $temp = generateUniquePokeID();
                break;
            }
        }
    
        return $temp;
    }

    public static function getAccountsFile() : String {
        return $_SERVER['DOCUMENT_ROOT'] . "/../accounts.json";
    }
}
?>