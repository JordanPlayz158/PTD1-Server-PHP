<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

if(getallheaders()['X-Forwarded-Proto'] == "http") {
    echo 'You are unable to access the logging login page via http, please connect through https in order to do so!';
    return;
}

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $post_data = array();

    $body = file_get_contents('php://input');
    $body = htmlentities($body, ENT_QUOTES, 'UTF-8');
    $body = str_replace("&amp;", "&", $body);

    foreach(explode("&", $body) as $urlVariable) {
        $keyAndValue = explode("=", $urlVariable);

        $post_data[$keyAndValue[0]] = $keyAndValue[1];
    }

    Utils::setEmptyFileContents(Utils::getConfigFile(), Utils::getConfigFileDefault());

    Utils::$config = $config = json_decode(file_get_contents(Utils::getConfigFile()), true);

    if($config['pass'] == $post_data['Pass']) {
        if(strlen($config['timezone']) > 0) {
            date_default_timezone_set($config['timezone']);
        }
        
        require($_SERVER['DOCUMENT_ROOT'] . '/../logging/logging.php');
        return;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logging - Login</title>
    <link rel="stylesheet" type="text/css" href="logging.css">
</head>
<body>
    <h1>Login</h1>
    <form id="login" method="post" action="index.php">
        <label><b>Password</b></label>
        <input type="Password" name="Pass" id="Pass" placeholder="Password">
        <br>
        <input type="submit" name="log" id="log" value="Log In">
        <br>
        <a>Forgot Password? If so, please check your config.json!</a>
    </form>
</body>
</html>