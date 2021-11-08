<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_data = array();

    $body = file_get_contents('php://input');
    $body = urldecode($body);

    foreach (explode("&", $body) as $urlVariable) {
        $keyAndValue = explode("=", $urlVariable);

        $post_data[$keyAndValue[0]] = $keyAndValue[1];
    }

    Utils::response("Result", "Success");
    Utils::response("Reason", "GetAchive");

//need to respond with Ach1-Ach14
    Utils::response("Ach1", "1111");
    Utils::response("Ach2", "1");
    Utils::response("Ach3", "1");
    Utils::response("Ach4", "1");
    Utils::response("Ach5", "1");
    Utils::response("Ach6", "1");
    Utils::response("Ach7", "1");
    Utils::response("Ach8", "1");
    Utils::response("Ach9", "1");
    Utils::response("Ach10", "1");
    Utils::response("Ach11", "1");
    Utils::response("Ach12", "1");
    Utils::response("Ach13", "1");
    Utils::response("Ach14", "1");

    echo Utils::getResponse();
} else {
    echo "Invalid Request Method";
}
?>