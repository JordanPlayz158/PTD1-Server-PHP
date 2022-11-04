<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;

use League\OAuth2\Client\Provider\Google;

require 'vendor/autoload.php';

class Mail {
    public PHPMailer $mailer;

    function __construct($config) {
        $mailConfig = $config['mail'];
        $mailer = new PHPMailer();
        $mailer->isSMTP();
        $mailer->Host = $mailConfig['host'];
        $mailer->Port = $mailConfig['port'];
        $mailer->SMTPSecure = $mailConfig['encryption'];
        $mailer->SMTPAuth = true;
        $mailer->AuthType = 'XOAUTH2';

        $email = $mailConfig['email'];
        $clientId = $mailConfig['clientId'];
        $clientSecret = $mailConfig['clientSecret'];
        $refreshToken = $mailConfig['refreshToken'];

        $provider = new Google(
            [
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
            ]
        );

        $mailer->setOAuth(
            new OAuth(
                [
                    'provider' => $provider,
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                    'refreshToken' => $refreshToken,
                    'userName' => $email,
                ]
            )
        );

        // Get and save a new refresh token into the config so it never expires
        // In order to ensure it never expires, make it run as a cronjob

        $this->mailer = $mailer;
    }

}
