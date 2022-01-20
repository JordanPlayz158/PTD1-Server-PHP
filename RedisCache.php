<?php
class RedisCache {
    public Redis $conn;

    function __construct(array $config) {
        $this->conn = new Redis();
        $redisConfig = $config['redis'];
        $port = $redisConfig['port'];
        $pass = $redisConfig['pass'];

        if($port === -1) {
          $port = 6379;
        }

        if($this->conn->connect('127.0.0.1', $port)) {
            if(strlen($pass) > 0) {
              $this->conn->auth($pass);
            }
        }
    }
}
