<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Log.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/php/newPoke8.php');

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

    // If the file is empty or under 2 characters in length (invalid json) then the file will be overwritten with the desired contents from $string
    public static function setEmptyFileContents($file, $string) {
        if(!file_exists($file) || strlen(file_get_contents($file)) < 2) {
            $file = fopen($file, 'w');
            fwrite($file, $string);
            fclose($file);
        }
    }

    public static function log() {
        $log = new Log();

        $log -> time = time();
        $log -> ip = getallheaders()['X-Forwarded-For'];
        $log -> post_data = newPoke8::$body;

        $response = Utils::getResponse();
        $responseData = array();

        foreach(explode("&", $response) as $urlVariable) {
            $keyAndValue = explode("=", $urlVariable);
    
            $responseData[$keyAndValue[0]] = $keyAndValue[1];
        }

        $log -> response = 'Result=' . $responseData['Result'] . '&Reason=' . $responseData['Reason'];

        Utils::setEmptyFileContents(Utils::getLogFile(), "[]");
        $logJson = json_decode(file_get_contents(Utils::getLogFile()), true);
        $logs = array();

        foreach($logJson as $json) {
            $logA = new Log();

            $logA -> time = $json['time'];
            $logA -> ip = $json['ip'];
            $logA -> post_data = $json['post_data'];
            $logA -> response = $json['response'];

            $logs[] = $logA;
        }

        $logs[] = $log;

        $file = fopen(Utils::getLogFile(), 'w');
        fwrite($file, json_encode($logs));
        fclose($file);
    }

    public static function getAccountsFile() : String {
        return $_SERVER['DOCUMENT_ROOT'] . "/../accounts.json";
    }

    public static function getLogFile() : String {
        return $_SERVER['DOCUMENT_ROOT'] . "/../log.json";
    }

    public static function getConfigFile() : String {
        return $_SERVER['DOCUMENT_ROOT'] . "/../config.json";
    }
}
?>