<?php
class Account {
    public string $email;
    public string $pass;

    public array $saves = array();

    public ?string $accNickname = null;
    public ?string $dex1 = null;
    public ?string $dex1Shiny = null;
    public ?string $dex1Shadow = null;

    public function parse(array $account) {
        $this->email = $account['email'];
        $this->pass = $account['pass'];
        $this->accNickname = $account['accNickname'];
        $this->dex1 = $account['dex1'];
        $this->dex1Shiny = $account['dex1Shiny'];
        $this->dex1Shadow = $account['dex1Shadow'];
    }
}