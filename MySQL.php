<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../objects/Item.php';

class MySQL {
    public $conn;

    function __construct() {
        $mysqlConfig = Utils::$config['mysql'];

        //$driver = new mysqli_driver();
        //$driver->report_mode = MYSQLI_REPORT_ALL;

        $this->conn = $conn = new mysqli($mysqlConfig['hostname'], $mysqlConfig['username'], $mysqlConfig['password'], $mysqlConfig['db']);

        if($conn->connect_error) {
            // How to check if mariadb database is offline
            // Reason = 'DatabaseConnection'
            if($conn->connect_error === 'Connection refused') {
                echo 'Result=Failure&Reason=DatabaseConnection';
                exit;
            }
            die('Database connection failed: ' . $conn->connect_error);
        }

        $makeAccountsTable = 'CREATE TABLE IF NOT EXISTS accounts (
            email VARCHAR(255) UNIQUE NOT NULL,
            pass VARCHAR(255) NOT NULL,
            trainerId MEDIUMINT(5) unsigned NOT NULL,
            accNickname VARCHAR(255),
            dex1 VARCHAR(151),
            dex1Shiny VARCHAR(151),
            dex1Shadow VARCHAR(151)
        ); ';

        $makeSavesTable = 'CREATE TABLE IF NOT EXISTS saves (
            id VARCHAR(255) UNIQUE NOT NULL,
            advanced TINYINT(3) unsigned,
            advanced_a TINYINT(3) unsigned,
            nickname VARCHAR(255),
            badges TINYINT(3) unsigned,
            avatar VARCHAR(4),
            classic TINYINT(3) unsigned,
            classic_a VARCHAR(255),
            challenge TINYINT(3) unsigned,
            money INT(10) unsigned,
            npcTrade TINYINT(1) unsigned,
            shinyHunt TINYINT(1) unsigned,
            version TINYINT(1) unsigned
        ); ';

        $makePokesTable = 'CREATE TABLE IF NOT EXISTS pokes (
            id VARCHAR(255) UNIQUE NOT NULL,
            reason VARCHAR(255),
            num MEDIUMINT(6) unsigned,
            nickname VARCHAR(255),
            exp MEDIUMINT(7) unsigned,
            lvl TINYINT(3) unsigned,
            m1 SMALLINT(5) unsigned,
            m2 SMALLINT(5) unsigned,
            m3 SMALLINT(5) unsigned,
            m4 SMALLINT(5) unsigned,
            ability SMALLINT(5) unsigned,
            mSel TINYINT(1) unsigned,
            targetType TINYINT(1) unsigned,
            tag VARCHAR(3),
            item VARCHAR(3),
            owner VARCHAR(255),
            myID MEDIUMINT(6) unsigned,
            pos MEDIUMINT(7) unsigned,
            shiny TINYINT(1) unsigned
        ); ';

        $makeItemsTable = 'CREATE TABLE IF NOT EXISTS items (
            id VARCHAR(255) UNIQUE NOT NULL,
            num TINYINT(3) unsigned
        ); ';

        $makeLogsTable = 'CREATE TABLE IF NOT EXISTS logs (
            time INT(10) unsigned,
            ip VARCHAR(255),
            post_data LONGTEXT,
            response LONGTEXT
        ); ';

        $makeTables = $makeAccountsTable . $makeSavesTable . $makePokesTable . $makeItemsTable . $makeLogsTable;
        if($conn->multi_query($makeTables) or die($conn->error)) {
            do {
                if ($result = $conn -> store_result()) {
                    $result -> free_result();
                }
            } while ($conn -> next_result());
        }
    }

    public function createAccount($account) {
        $conn = $this->conn;

        $stmt = $conn->prepare('INSERT INTO accounts VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssissss', $account->email, $account->pass, $account->trainerId, $account->accNickname, $account->dex1, $account->dex1Shiny, $account->dex1Shadow);
        $stmt->execute() or $stmt->close() && $conn->close() && die('Result=Failure&Reason=taken');
        $stmt->close();
        
        $stmt = $conn->prepare('INSERT INTO saves VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        
        $saves = $account->saves;
        for($i = 0; $i < count($saves); $i++) {
            $save = $saves[$i];
            $id = $account->email . ',' . $i;

            $stmt->bind_param('siisisisiiiii', $id, $save->advanced, $save->advanced_a, $save->nickname, $save->badges, $save->avatar, $save->classic, $save->classic_a, $save->challenge, $save->money, $save->npcTrade, $save->shinyHunt, $save->version);
            $stmt->execute();
        }
        
        $stmt->close();
        
    }
    
    public function saveAccount($account) {
        $conn = $this->conn;
        
        $stmts = array();
        $stmts[] = $stmt = $conn->prepare('UPDATE accounts SET trainerId = ?, accNickname = ?, dex1 = ?, dex1Shiny = ?, dex1Shadow = ? WHERE email = ?');
        $stmt->bind_param('isssss', $account->trainerId, $account->accNickname, $account->dex1, $account->dex1Shiny, $account->dex1Shadow, $account->email);
        $stmt->execute();
        
        for($i = 0; $i < count($account->saves); $i++) {
            $save = $account->saves[$i];
            
            $id = $account->email . ',' . $i;
            
            $stmts[] = $stmt = $conn->prepare('UPDATE saves SET advanced = ?, advanced_a = ?, nickname = ?, badges = ?, avatar = ?, classic = ?, classic_a = ?, challenge = ?, money = ?, npcTrade = ?, shinyHunt = ?, version = ? WHERE id = ?') or die($conn->errno);
            $stmt->bind_param('iisisisiiiiis', $save->advanced, $save->advanced_a, $save->nickname, $save->badges, $save->avatar, $save->classic, $save->classic_a, $save->challenge, $save->money, $save->npcTrade, $save->shinyHunt, $save->version, $id);
            $stmt->execute();


            //print_r($save-> pokes);
            foreach($save -> pokes as $poke) {
                $id = $poke->id;
                $pokeReason = $poke-> reason;
                $pokeNum = $poke-> num;
                $pokeNickname = $poke-> nickname;
                $pokeExp = $poke-> exp;
                $pokeLvl = $poke-> lvl;
                $pokeM1 = $poke-> m1;
                $pokeM2 = $poke-> m2;
                $pokeM3 = $poke-> m3;
                $pokeM4 = $poke-> m4;
                $pokeAbility = $poke-> ability;
                $pokeMSel = $poke-> mSel;
                $pokeTargetType = $poke-> targetType;
                $pokeTag = $poke-> tag;
                $pokeItem = $poke-> item;
                $pokeOwner = $poke-> owner;
                $pokeMyID = $poke-> myID;
                $pokePos = $poke-> pos;
                $pokeShiny = $poke-> shiny;

                //print_r($poke);
                
                $stmts[] = $stmt = $conn->prepare('INSERT INTO pokes VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE reason=?, num=?, nickname=?, exp=?, lvl=?, m1=?, m2=?, m3=?, m4=?, ability=?, mSel=?, targetType=?, tag=?, item=?, owner=?, myID=?, pos=?, shiny=?');
                $stmt->bind_param('ssisiiiiiiiiisssiiisisiiiiiiiiisssiii', $id, $pokeReason, $pokeNum, $pokeNickname, $pokeExp, $pokeLvl, $pokeM1, $pokeM2, $pokeM3,
                                    $pokeM4, $pokeAbility, $pokeMSel, $pokeTargetType, $pokeTag, $pokeItem, $pokeOwner, $pokeMyID, $pokePos, $pokeShiny, $pokeReason,
                                    $pokeNum, $pokeNickname, $pokeExp, $pokeLvl, $pokeM1, $pokeM2, $pokeM3, $pokeM4, $pokeAbility, $pokeMSel, $pokeTargetType,
                                    $pokeTag, $pokeItem, $pokeOwner, $pokeMyID, $pokePos, $pokeShiny);
                $stmt->execute();

                //echo $id . $pokeReason . $pokeNum . $pokeNickname . $pokeExp . $pokeLvl . $pokeM1 . $pokeM2 . $pokeM3 .
                //                    $pokeM4 . $pokeAbility . $pokeMSel . $pokeTargetType . $pokeTag . $pokeItem . $pokeOwner . $pokeMyID . $pokePos . $pokeShiny;
            }
            
            foreach($save->items as $item) {
                $id = $account->email . ',' . $i . ',' . $item->id;
                $itemNum = $item->num;
                
                $stmts[] = $stmt = $conn->prepare('INSERT INTO items VALUES(?, ?) ON DUPLICATE KEY UPDATE num=?');
                $stmt->bind_param('sii', $id, $itemNum, $itemNum);
                $stmt->execute();
            }
        }
        
        foreach($stmts as $stmt) {
            $stmt->close();
        }
    }
}
?>