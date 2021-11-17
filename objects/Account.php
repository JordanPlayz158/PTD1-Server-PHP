<?php
class Account {
    public string $email;
    public string $pass;

    public array $saves = array();

    public ?string $accNickname = null;
    public ?string $dex1 = null;
    public ?string $dex1Shiny = null;
    public ?string $dex1Shadow = null;
}