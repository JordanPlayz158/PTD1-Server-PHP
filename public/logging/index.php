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

    Utils::setEmptyFileContents(Utils::getLogFile(), "[]");
    Utils::setEmptyFileContents(Utils::getConfigFile(), "{\n  \"Pass\":\"" . generatePass(32) . "\",\n  \"timezone\":\"\"\n}");

    $config = json_decode(file_get_contents(Utils::getConfigFile()), true);

    if($config['Pass'] == $post_data['Pass']) {
        if(strlen($config['timezone']) > 0) {
            date_default_timezone_set($config['timezone']);
        }

        // Add 3 buttons for post_data (Original, URL Decoded (or just Decoded), and Pretty)
        require($_SERVER['DOCUMENT_ROOT'] . '/../logging/logging.php');
        return;
    }
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color:#6abadeba;
            font-family: 'Arial';
        }
        .login {
            width: 382px;
            overflow: hidden;
            margin: auto;
            margin: 20 0 0 450px;
            padding: 80px;
            background: #23463f;
            border-radius: 15px ;
        }
        h2 {
            text-align: center;
            color: #277582;
            padding: 20px;
        }
        label {
            color: #08ffd1;
            font-size: 17px;
        }
        #Pass {
            width: 300px;
            height: 30px;
            border: none;
            border-radius: 3px;
            padding-left: 8px;
        }
        #log {
            width: 300px;
            height: 30px;
            border: none;
            border-radius: 17px;
            padding-left: 7px;
            color: blue;
        }
        span {
            color: white;
            font-size: 17px;
        }
    </style>
</head>
<body>
    <h2>Login Page</h2><br>
    <div class="login">
    <form id="login" method="post" action="index.php">
        <label><b>Password
        </b>
        </label>
        <input type="Password" name="Pass" id="Pass" placeholder="Password">
        <br><br>
        <input type="submit" name="log" id="log" value="Log In">
        <br><br>
        Forgot Password? If so, please type <code>generateNewLoggingPassword</code> into server console to get a new one!</a>
    </form>
</div>
<a>Credits to: <a href="https://www.c-sharpcorner.com/article/creating-a-simple-login-page-using-html-and-css/">https://www.c-sharpcorner.com/article/creating-a-simple-login-page-using-html-and-css/</a></a>
</body>
</html>