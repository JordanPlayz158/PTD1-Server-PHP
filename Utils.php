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
        echo $conn->errno . " " . $conn->error;
        return null;
    }

    $bind = $stmt->bind_param('s', $email);

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

function logMySQL(mysqli $conn) {
    $response = urlVariablesToArray(getResponse());
    $responseResult = "Result={$response['Result']}";

    if(isset($response['Reason'])) {
        $responseResult .= "&Reason={$response['Reason']}";
    }

    $ip = getallheaders()['X-Forwarded-For'];
    $time = time();
    $body = file_get_contents('php://input');

    $stmt = $conn->prepare('INSERT INTO logs VALUES (?, ?, ?, ?);');
    $stmt->bind_param('isss', $time, $ip, $body, $responseResult);
    $stmt->execute();
    $stmt->close();
}