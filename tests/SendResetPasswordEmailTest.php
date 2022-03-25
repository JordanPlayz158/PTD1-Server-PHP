<?php
use PHPUnit\Framework\TestCase;

final class SendResetPasswordEmailTest extends TestCase {
    private array $config;
    private MySQL $mysql;

    public function testResetPasswordEmailCanBeSent(): void {
        $_SERVER['REQUEST_SCHEME'] = 'https';
        $_SERVER['SERVER_NAME'] = 'testserver.test';
        $_SERVER['DOCUMENT_ROOT'] = '/home/ptd1/upload/public';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->config = $config = require('config.php');
        require_once 'MySQL.php';
        $this->mysql = $mysql = new MySQL($config);

        $email = 'test@gmail.com';

        $mysql->deleteAccount($email);

        require 'actions/CreateAccount.php';
        $_POST['Email'] = $email;
        $_POST['Pass'] = 'test';
        require 'actions/LoadAccount.php';
        createAccount($mysql);

        $_POST = array();
        $_POST['email'] = $email;
        $expectedSubset = ['success' => true];

        ob_start();
        include 'public/api/sendResetPasswordForm/index.php';

        $actualArray = json_decode($this->getActualOutput(), true);
        ob_clean();

        foreach ($expectedSubset as $key => $value) {
            $this->assertArrayHasKey($key, $actualArray);
            $this->assertSame($value, $actualArray[$key]);
        }

        $mysql->deleteAccount($email);
    }

    public function testResetPasswordEmailEmptyEmail(): void {
        $_POST = array();
        $_POST['email'] = '';
        $expectedSubset = ['success' => false, 'errorCode' => 1];

        include 'public/api/sendResetPasswordForm/index.php';

        $actualArray = json_decode($this->getActualOutput(), true);
        ob_clean();
        print_r($actualArray);

        foreach ($expectedSubset as $key => $value) {
            $this->assertArrayHasKey($key, $actualArray);
            $this->assertSame($value, $actualArray[$key]);
        }
    }

    public function testResetPasswordEmailNoAtSymbol(): void {
        $_POST = array();
        $_POST['email'] = 'test';
        $expectedSubset = ['success' => false, 'errorCode' => 2];

        include 'public/api/sendResetPasswordForm/index.php';

        $actualArray = json_decode($this->getActualOutput(), true);
        ob_clean();

        print_r($actualArray);

        foreach ($expectedSubset as $key => $value) {
            $this->assertArrayHasKey($key, $actualArray);
            $this->assertSame($value, $actualArray[$key]);
        }
    }
}