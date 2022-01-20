<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../Mail.php');

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
            $mailer->addAddress($email);
            $mailer->Subject = 'Pokemon Tower Defense 1 - Password Reset';
            // Use Redis to store a temporary randomly generated string consisting of letters and numbers
            // that is tied to the email that's password is being set to the new password field
            $link = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/';

            $mailer->Body = "To reset your password click the link below\n\n$link";

            if($mailer->send()){
                echo "success";
            } else {
                echo 0;
                if(isset($_POST['debug'])) {
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                }
            }
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            echo 0;
            if(isset($_POST['debug'])) {
                echo 'Mailer Error: ' . $mail->ErrorInfo . "\n$e";
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

    $dns = array('1.1.1.1', '1.0.0.1', '8.8.8.8', '8.8.4.4');
    if(!dns_get_mx($split[$size - 1], $dns)) {
        return 3;
    }

    if($email === $config['mail']['username']) {
        return 8;
    }

    return true;
}