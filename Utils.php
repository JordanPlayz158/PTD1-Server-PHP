<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../RedisCache.php');
$response = '';

function urlVariablesToArray($urlVariables) : array {
    $responseData = array();

    foreach (explode('&', $urlVariables) as $urlVariable) {
        $keyAndValue = explode('=', $urlVariable);

        $responseData[$keyAndValue[0]] = $keyAndValue[1];
    }

    return $responseData;
}

function getResponse() : string {
    global $response;

    return $response;
}

function response(string $key, $value) {
    global $response;

    $response .= trim(chr(38 * (strlen($response) != 0))) . $key . '=' . $value;
}

function httpsOnly() {
    if ($_SERVER['REQUEST_SCHEME'] == "http") {
        exit('You are unable to access the logging login page via http, please connect through https in order to do so!');
    }
}

function getAccountDataByEmail(mysqli $conn, string $table, string $email) : array|null {
    $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");

    if(!$stmt) {
        echo $conn->errno . ' ' . $conn->error;
        return null;
    }

    $bind = $stmt->bind_param('s', $email);

    if(!$bind) {
        echo $conn->errno . ' ' . $conn->error;
        return null;
    }

    $execute = $stmt->execute();

    if(!$execute) {
        echo $conn->errno . ' ' . $conn->error;
        return null;
    }

    $meta = $stmt->result_metadata();
    while ($field = $meta->fetch_field()) {
        $params[] = &$row[$field->name];
    }

    $bind = call_user_func_array(array($stmt, 'bind_result'), $params);

    if(!$bind) {
        echo $conn->errno . ' ' . $conn->error;
        return null;
    }

    $result = null;

    while ($stmt->fetch()) {
        foreach ($row as $key => $val) {
            $c[$key] = $val;
        }
        $result[] = $c;
    }

    $stmt->close();

    return $result;
}

function logMySQL(mysqli $conn) {
    $response = urlVariablesToArray(getResponse());
    $responseResult = "Result={$response['Result']}";

    if(isset($response['Reason'])) {
        $responseResult .= "&Reason={$response['Reason']}";
    }

    $ip = $_SERVER['REMOTE_ADDR'];

    $time = time();
    $body = file_get_contents('php://input');

    $passVariable = strpos($body, "Pass=");
    $endOfAnd = strpos($body, "&", $passVariable);
    $body = substr($body, 0, $passVariable) . substr($body, $endOfAnd + 1);

    $stmt = $conn->prepare('INSERT INTO logs VALUES (?, ?, ?, ?);');
    $stmt->bind_param('isss', $time, $ip, $body, $responseResult);
    $stmt->execute();
    $stmt->close();
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
function generatePass(int $length, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') : String {
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    if ($max < 1) {
        throw new Exception('$keyspace must be at least two characters long');
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

function getEmail($config) : string {
    if(session_start()) {
        if(!isset($_SESSION['account_token'])) {
            echo json_encode([
                'success' => false,
                'error' => '"account_token" not set in session, please login at "/games/ptd/login.html"',
                'errorCode' => -1
            ]);
            exit();
        }
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Couldn\'t start session'
        ]);
        exit();
    }

    $redis = new RedisCache($config);
    $email = $redis->getSession();
    $redis->close();

    if(!$email) {
        echo json_encode([
            'success' => false,
            'error' => '"account_token" has expired',
            'errorCode' => -1
        ]);
        exit();
    }

    return $email;
}

function getIndividualSave(mysqli $conn, string $table, string $email, int $num) : ?array {
    $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ? AND num = ?");

    if(!$stmt) {
        echo $conn->errno . " " . $conn->error;
        return null;
    }

    $bind = $stmt->bind_param('si', $email, $num);

    if(!$bind) {
        echo $conn->errno . " " . $conn->error;
        return null;
    }

    $execute = $stmt->execute();

    if(!$execute) {
        echo $conn->errno . " " . $conn->error;
        return null;
    }

    $meta = $stmt->result_metadata();
    while ($field = $meta->fetch_field()) {
        $params[] = &$row[$field->name];
    }

    $bind = call_user_func_array(array($stmt, 'bind_result'), $params);

    if(!$bind) {
        echo $conn->errno . " " . $conn->error;
        return null;
    }

    $result = null;

    while ($stmt->fetch()) {
        foreach ($row as $key => $val) {
            $c[$key] = $val;
        }
        $result[] = $c;
    }

    $stmt->close();

    return $result;
}



/* FILTER vs SANITIZE advice
 * Is the API purely informational?
 * Then sanitizing is probably fine
 * although you can include warning messages as part of your response to help them debug.
 * But like.. sanitizing a request is "generally" fine for non-modifying calls.
 *
 * But like... if its something important like... deleting/creating stuff... or... "shoot armed the missile" (as an extreme example)
 * then sanitizing "1234Y567E890S" down to "YES"/true is probably ill advised.
 * But if its like "Hows company X doing on the stock market" then, if they have extra spaces or numbers or something, then..
 * maybe its fine as long as you maybe let them know that you sanitized their input and why in the response.
 */
function getSaves(mysqli $conn, string $email) : array|string {
    if(isset($_GET['debug'])) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    if(isset($_GET['save'])) {
        $saveString = $_GET['save'];

        $num = filter_var($saveString, FILTER_VALIDATE_INT, array(
            'options' => array(
                //'default' => 0, // value to return if the filter fails
                // other options here
                'min_range' => 0,
                'max_range' => 2
            ),
            'flags' => FILTER_FLAG_ALLOW_OCTAL,
        ));

        // Surely there has to be a better way
        $wasFalse = false;
        if($num === false) {
            $num = 0;
            $wasFalse = true;
        }

        $saves = getIndividualSave($conn, 'saves', $email, $num);

        // Talking about these 2
        if($wasFalse) {
            $saves['validation'] = "String '$saveString' was an invalid integer so it was replaced with default value '$num'. To remove this warning simply select one of the not currently selected profiles.";
        }
    } else {
        $savesMysql = getAccountDataByEmail($conn, 'saves', $email);
        $saves = array();

        foreach ($savesMysql as $saveArray) {
            $save = new Save();
            $save->parse($saveArray);

            $saves[] = $save;
        }
    }

    if (!isset($_GET['exclude']) || !in_array('pokes' , explode(',', $_GET['exclude']))) {
        $pokes = getPokes($conn, $email);

        if(isset($num)) {
            $saves[0]['pokes'] = $pokes[0];
        } else {
            // Pokemon
            for ($i = 0; $i <= 2; $i++) {
                $saves[$i]->pokes = $pokes[$i];
            }
        }
    }

    return $saves;
}

function getPokes(mysqli $conn, string $email) : array {
    $num = false;

    if(isset($_GET['save'])) {
        $num = $_GET['save'];
    }

    if ($num !== false) {
        $stmt = $conn->prepare("SELECT *
       FROM pokes poke
       WHERE email = ? AND num = ? AND NOT EXISTS (SELECT *
                                FROM trades trade
                                WHERE trade.email = poke.email
                                      AND trade.num = poke.num
                                      AND trade.id = poke.id);");

        $stmt->bind_param('si', $email, $num);
    } else {
        $stmt = $conn->prepare("SELECT *
       FROM pokes poke
       WHERE email = ? AND NOT EXISTS (SELECT *
                                FROM trades trade
                                WHERE trade.email = poke.email
                                      AND trade.num = poke.num
                                      AND trade.id = poke.id);");

        $stmt->bind_param('s', $email);
    }

    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    $pokes = array();
    while (($row = $result->fetch_assoc()) != null) {
        $poke = new Poke();
        $poke->parse($row);
        $poke->saveNum = $row['num'];

        if($num !== false) {
            $pokes[0][] = $poke;
        } else {
            $pokes[$row['num']][] = $poke;
        }
    }

    return $pokes;
}