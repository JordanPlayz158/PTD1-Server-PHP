<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

if(getallheaders()['X-Forwarded-Proto'] == "http") {
    echo 'You are unable to access the logging login page via http, please connect through https in order to do so!';
    return;
}

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $body = str_replace("&amp;", "&", htmlentities(file_get_contents('php://input'), ENT_QUOTES, 'UTF-8'));
    $post_data = Utils::urlVariablesToArray($body);

    if (session_start()) {
        // Change from password to actual random token that expires in 24 hours.
        $_SESSION['token'] = $post_data['pass'];
        header('Location: /logging/');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Logging - Login</title>
    <link rel="stylesheet" type="text/css" href="logging.css">
</head>
<body>
    <h1>Login</h1>
    <form id="login" method="post" action="login.php">
        <label><b>Password</b></label>
        <label for="pass"></label><input type="Password" name="pass" id="pass" placeholder="Password">
        <br>
        <input type="submit" name="log" id="log" value="Log In">
        <br>
        <a>Forgot Password? If so, please check your config.json!</a>
    </form>
</body>
</html>