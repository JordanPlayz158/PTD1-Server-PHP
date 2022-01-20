<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../RedisCache.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php');

if(isset($_GET['debug'])) {
    //echo phpinfo();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    if (!$_POST) {
        exit("Postdata must be \"x-www-form-urlencoded\"");
    } else if (!isset($_POST['email'])) {
        exit("\"email\" postdata parameter must be set");
    } else if (!isset($_POST['pass'])) {
        exit("\"pass\" postdata parameter must be set");
    }

    $accounts = getAccountDataByEmail($conn, 'accounts', $_POST['email']);

    if (count($accounts) === 1) {
        $account = new Account();
        $account->parse($accounts[0]);

        if (password_verify($_POST['pass'], $account->pass)) {
            $redis = new RedisCache($config);
            $redisConn = $redis->conn;

            $token = generatePass(64);
            $redisConn->set($token, $account->email, 86400);

            session_start();
            $_SESSION['account_token'] = $token;
            header('Location: /games/ptd/account.php');
        }
    }

    exit();
} else if($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo "Invalid Request Method! Accepted Method(s): GET, POST";
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
<html lang="en">
<head>
    <title>Save Selection</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <!--<script type='text/javascript' src='logging.js'></script>-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
    </script>
    <script>
        $(function () {
            $("#header").load("../../_static/html/header.html");
            $("#nav").load("../../_static/html/nav.html");
        });

        $.ajax({
            url: "/api/getSaves/?exclude=pokes",
            type: "GET",
            success: function (result) {
                const saves = JSON.parse(result);
                let html = "";

                let counter = 0;
                saves.forEach(save => {
                    html += "<button onclick=\"location.href = '/games/ptd/trading.html?save=" + counter + "';\"><div class='block content'>" + save['avatar'] + "<br>" + save['nickname'] + "</div></button><br><br>";

                    console.log(save['nickname']);
                    console.log(save['avatar']);

                    counter++;
                });

                document.getElementById('contentDiv').innerHTML = html;
                //console.log(saves);
            },
            error: function (error) {
                console.log(error);
            },
        })
    </script>
</head>
<body>
<div id="header"></div>
<div id="content">
    <div id="nav"></div>
    <table id="content_table">
            <td id="main">
                <div class="block center">
                    <div class="title">
                        <p>Profile Save Selection</p>
                    </div>
                    <div class="content" id="contentDiv"></div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
