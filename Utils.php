<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Log.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Keygen.php');

class Utils {
    public static MySQL $mysql;
    private static string $response = '';
    public static array $config;

    public static function urlVariablesToArray($urlVariables) : array {
        $responseData = array();

        foreach (explode('&', $urlVariables) as $urlVariable) {
            $keyAndValue = explode('=', $urlVariable);
    
            $responseData[$keyAndValue[0]] = $keyAndValue[1];
        }

        return $responseData;
    }

    public static function getPokeByID($pokes, $id) : Poke {
        foreach($pokes as $poke) {
            echo $poke->myID . ' ' . $id;

            if($poke->myID == $id)
                return $poke;
        }
    
        return new Poke();
    }

    public static function setPokeData($post_data, Poke $poke, $postKey, $pokeVariable) {    
        if(isset($post_data[$postKey]))
            $poke -> $pokeVariable = $post_data[$postKey];
    }

    public static function getResponse() : string {
        global $response;

        return $response;
    }
    
    public static function response(string $key, $value) {
        global $response;
    
        $response .= trim(chr(38 * (strlen($response) != 0))) . $key . '=' . $value;
    }
    
    /*public static function generateValidTrainerID($trainerIds) : int {
        $valid = false;

        while (!$valid) {
            $tmp = mt_rand(333, 99999);
            $valid = true;

            foreach ($trainerIds as $trainerId) {
                if ($tmp == $trainerId[0]) {
                    $valid = false;
                    break;
                }
            }
        }
        
        return $tmp;
    }*/
    
    public static function generateValidProfileID(/*$trainerID*/) : string {
        // this keygen is just for a hacker check, so I hardcoded the profileId
        // if for whatever reason you want to have random trainer and profileIds then
        // I'll leave the Keygen code in just in case (and as we made it look nice and perform well)
        //return generateProfileId(10000000000000, $trainerID);

        //generateProfileId(10000000000000, 333); results in the output below
        return 'ikkg';
    }
    
    public static function generateUniquePokeID($pokes) : int {
        $valid = false;
        
        while (!$valid) {
            $tmp = mt_rand(1, 999999);
            $valid = true;

            foreach ($pokes as $poke) {
                if ($tmp == $poke->myID) {
                    $valid = false;
                    break;
                }
            }
        }

        return $tmp;
    }

    public static function generateUniqueID($ids) : int {
        $valid = false;
        
        while (!$valid) {
            $tmp = mt_rand(1, 999999);
            $valid = true;

            foreach($ids as $id) {
                if ($tmp == $id[0]) {
                    $valid = false;
                    break;
                }
            }
        }

        return $tmp;
    }

    // If the file is empty or under 2 characters in length (invalid json) then the file will be overwritten with the desired contents from $string
    public static function setEmptyFileContents($file, $string) {
        if (!file_exists($file) || strlen(file_get_contents($file)) < 2) {
            $file = fopen($file, 'w');
            fwrite($file, $string);
            fclose($file);
        }
    }

    /**
     * Generate a random string, using a cryptographically secure
     * pseudorandom number generator (random_int)
     *
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     *
     * @param int $length How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     * @throws Exception
     */
    private static function generatePass(int $length, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') : string {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;

        if ($max < 1)
            throw new Exception('$keyspace must be at least two characters long');

        for ($i = 0; $i < $length; ++$i)
            $str .= $keyspace[random_int(0, $max)];

        return $str;
    }

    /**
     * @throws Exception
     */
    public static function getConfigFileDefault() : string {
        return "{\n  \"maintenance\": false,\n  \"timezone\": \"\",\n  \"pass\":
                     \"" . Utils::generatePass(32) . "\",\n  \"mysql\": {\n
                        \"hostname\": \"\",\n    \"username\": \"\",\n 
                               \"password\": \"\",\n    \"db\": \"\"\n  }\n}";
    }

    public static function getConfigFile() : string {
        return $_SERVER['DOCUMENT_ROOT'] . '/../config.json';
    }
}