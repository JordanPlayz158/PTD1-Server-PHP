<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../Mail.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../RedisCache.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');
    $isValid = validEmail($email, $config);
    if($isValid === true) {
        // Check if email is in db
        // Change alert message of success to say something like "email sent if email was in db"

        $mail = new Mail($config);

        $mailer = $mail->mailer;
        try {
            $redis = new RedisCache($config);
            $redisConn = $redis->conn;

            $token = generatePass(64);
            $redisConn->set('resetPassword.' . $token, $email, 86400);

            $mailer->addAddress($email);
            $mailer->Subject = 'Pokemon Tower Defense 1 - Password Reset';
            // Use Redis to store a temporary randomly generated string consisting of letters and numbers
            // that is tied to the email that's password is being set to the new password field
            $link = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/games/ptd/reset_password.html?reset_token=' . $token;

            $mailer->Body = "To reset your password click the link below\n\n$link\n\nPassword Reset links expire in 24 hours!";

            if($mailer->send()){
                echo "success";
            } else {
                echo 0;
                if(isset($_POST['debug'])) {
                    echo 'Mailer Error: ' . $mailer->ErrorInfo;
                }
            }
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            echo 0;
            if(isset($_POST['debug'])) {
                echo 'Mailer Error: ' . $mailer->ErrorInfo . "\n$e";
            }
        }
    } else {
        echo $isValid;
    }

    return;
}

function validEmail(string $email, array $config) : bool|int {
    if(empty($email)) {
        return 1;
    }

    $split = explode('@', $email);
    $size = sizeof($split);
    if($size < 2) {
        return 2;
    }

    if(strtolower($email) == strtolower($config['mail']['username'])) {
        return 8;
    }

    $mysql = new MySQL($config);
    $conn = $mysql->conn;
    $accounts = getAccountDataByEmail($conn, 'accounts', $email);

    if($accounts === null || sizeof($accounts) != 1) {
        return 4;
    }

    $dns = array('1.1.1.1', '1.0.0.1', '8.8.8.8', '8.8.4.4');
    if(!dns_get_mx($split[$size - 1], $dns)) {
        return 3;
    }

    return true;
}