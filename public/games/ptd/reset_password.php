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

            $mailer->Body = "To reset your password click the link below\n\n{LINK PLACEHOLDER}";

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='../../_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='../../_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="../../_static/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
    </script>
    <script type='text/javascript' src='../../_static/js/validation.js'></script>
    <script>
        $(function () {
            $("#header").load("header.html");
            $("#nav").load("nav.html");
        });
    </script>
</head>
<body>
<div id="header"></div>
<div id="content">
    <div id="nav"></div>
    <table id="content_table">
        <tbody>
        <tr>
            <td id="sidebar">
                <div class="block">
                    <div class="title">
                        <p>Forgot Password</p>
                    </div>
                    <div class="content">
                        <p>Please enter your email below to get an email to reset your password!</p>
                        <form id="forgotPassword" action="javascript:void(0);" onsubmit="validate()" method="post">
                            <br>
                            <label><b>Email:</b>
                                <input id="email" class="text" name="email" type="text">
                            </label>
                            <br>
                            <br>
                            <div class="login_actions">
                                <input value="Send" type="submit" class="login_btn">
                            </div>
                        </form>
                    </div>
                </div>
            </td>
            <td id="main">
                <div class="block">
                    <div class="title">
                        <p>Pokémon Tower Defense: Forgot Password</p>
                    </div>
                    <div class="content">
                        <p>So you forgot your password, it happens to the best of us but you're in luck, if you used an authentic email address (that you have access to) when making your account then you can reset your password with the link sent to your email!</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>