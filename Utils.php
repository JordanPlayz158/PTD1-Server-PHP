<?php
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
    if (getallheaders()['X-Forwarded-Proto'] == "http") {
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
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function generatePass($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') : String {
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