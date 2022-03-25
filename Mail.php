<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;

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
        $mailer->Username = $mailConfig['username'];
        $mailer->Password = $mailConfig['password'];
        $this->mailer = $mailer;
    }

}