<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

class RedisCache {
    public Redis $conn;
    private string $prefix = 'ptd1.';
    private string $sessionPrefix = 'sessions.';
    private string $resetPasswordPrefix = 'resetPassword.';

    function __construct(array $config) {
        $this->conn = new Redis();
        $redisConfig = $config['redis'];
        $host = $redisConfig['host'];
        $port = $redisConfig['port'];
        $pass = $redisConfig['pass'];

        if($port === -1) {
          $port = 6379;
        }

        if($this->conn->connect($host, $port)) {
            if(strlen($pass) > 0) {
              $this->conn->auth($pass);
            }
        }
    }

    /*public function generateToken() : false|string {
        try {
            return generatePass(64);
        } catch (Exception) {
            return json_encode([
                'success' => false,
                'error' => 'Generating the token failed',
                'errorCode' => 5
            ]);
        }
    }*/

    public function getSession() : string|false {
        return $this->conn->get($this->prefix . $this->sessionPrefix . $_SESSION['account_token']);
    }

    public function setSession(string $token, string $email) : bool {
        return $this->conn->set($this->prefix . $this->sessionPrefix . $token, $email, 86400);
    }

    public function getResetPassword(string $resetPasswordKey): string|false {
        return $this->conn->get($this->prefix . $this->resetPasswordPrefix . $resetPasswordKey);
    }

    public function setResetPassword(string $resetPasswordKey, string $email): bool {
        return $this->conn->set($this->prefix . $this->resetPasswordPrefix . $resetPasswordKey, $email, 86400);
    }

    public function deleteResetPassword(string $resetPasswordKey): int {
        return $this->conn->del($this->prefix . $this->resetPasswordPrefix . $resetPasswordKey);
    }

    public function close(): bool {
        return $this->conn->close();
    }
}
