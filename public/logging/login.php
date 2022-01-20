<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

httpsOnly();

if($_SERVER['REQUEST_METHOD'] == "POST") {
    if (session_start()) {
        // Change from password to actual random token that expires in 24 hours.
        $_SESSION['token'] = $_POST['pass'];
        header('Location: /logging/');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Logging - Login</title>
    <link rel="stylesheet" type="text/css" href="../games/ptd/admin/admin.css">
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