<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php';

class MySQL {
    public mysqli $conn;

    function __construct() {
        $mysqlConfig = Utils::$config['mysql'];

        //$driver = new mysqli_driver();
        //$driver->report_mode = MYSQLI_REPORT_STRICT|MYSQLI_REPORT_ERROR;

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
            email VARCHAR(255) NOT NULL,
            pass VARCHAR(255) NOT NULL,
            accNickname VARCHAR(255),
            dex1 VARCHAR(151),
            dex1Shiny VARCHAR(151),
            dex1Shadow VARCHAR(151),
    
            PRIMARY KEY(email),
            FOREIGN KEY(email) REFERENCES saves(email)
        ); ';

        $makeSavesTable = 'CREATE TABLE IF NOT EXISTS saves (
            email VARCHAR(255) NOT NULL,
            num TINYINT(1) unsigned NOT NULL,
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
            version TINYINT(1) unsigned,
            items LONGTEXT,
    
            PRIMARY KEY(email, num),
            FOREIGN KEY (email, num) REFERENCES pokes(email, num)
        ); ';

        $makePokesTable = 'CREATE TABLE IF NOT EXISTS pokes (
            email VARCHAR(255) NOT NULL,
            num TINYINT(1) unsigned NOT NULL,
            id MEDIUMINT(7) unsigned NOT NULL,
            pNum MEDIUMINT(6) unsigned,
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
            pos MEDIUMINT(7) unsigned,
            shiny TINYINT(1) unsigned,
    
            PRIMARY KEY(email, num, id)
        ); ';

        $makeLogsTable = 'CREATE TABLE IF NOT EXISTS logs (
            time INT(10) unsigned,
            ip VARCHAR(255),
            post_data LONGTEXT,
            response LONGTEXT
        ); ';

        $makeTables = $makePokesTable . $makeSavesTable . $makeAccountsTable . $makeLogsTable;
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
        $email = str_replace('%', '', $account->email);

        $stmt = $conn->prepare('INSERT INTO accounts VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssss', $email, $account->pass, $account->accNickname, $account->dex1, $account->dex1Shiny, $account->dex1Shadow);
        $stmt->execute() or $stmt->close() && $conn->close() && die('Result=Failure&Reason=taken');
        $stmt->close();
        
        $stmt = $conn->prepare('INSERT INTO saves VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        
        $saves = $account->saves;
        for($i = 0; $i < count($saves); $i++) {
            $save = $saves[$i];
            $id = $account->email . ',' . $i;
            $items = serialize($save->items);

            // Add items as last bind param
            $stmt->bind_param('siisisisiiiiis', $id, $save->advanced, $save->advanced_a, $save->nickname, $save->badges, $save->avatar, $save->classic, $save->classic_a, $save->challenge, $save->money, $save->npcTrade, $save->shinyHunt, $save->version, $items);
            $stmt->execute();
        }
        
        $stmt->close();
    }
    
    public function saveAccount($account) {
        $conn = $this->conn;

        $accountStmt = $conn->prepare('UPDATE accounts SET accNickname = ?, dex1 = ?, dex1Shiny = ?, dex1Shadow = ? WHERE id = ?');
        $accountStmt->bind_param('sssss', $account->accNickname, $account->dex1, $account->dex1Shiny, $account->dex1Shadow, $account->email);
        $accountStmt->execute();
        $accountStmt->close();

        $savesStmt = $conn->prepare('UPDATE saves SET advanced = ?, advanced_a = ?, nickname = ?, badges = ?, avatar = ?, classic = ?, classic_a = ?, challenge = ?, money = ?, npcTrade = ?, shinyHunt = ?, version = ?, items = ? WHERE id = ?') or die($conn->errno);

        for($i = 0; $i < count($account->saves); $i++) {
            $save = $account->saves[$i];
            
            $id = $account->email . ',' . $i;
            $items = serialize($save->items);

            $savesStmt->bind_param('iisisisiiiiiss', $save->advanced, $save->advanced_a, $save->nickname, $save->badges, $save->avatar, $save->classic, $save->classic_a, $save->challenge, $save->money, $save->npcTrade, $save->shinyHunt, $save->version, $items, $id);
            $savesStmt->execute();

            foreach($save -> pokes as $poke) {
                $id = $account->email . ',' . $i . ',' . $poke->myID;
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
                $pokePos = $poke-> pos;
                $pokeShiny = $poke-> shiny;

                switch ($poke -> reason) {
                    case "cap":

                        break;
                }
                $pokeStmt = $conn->prepare('INSERT INTO pokes VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE num=?, nickname=?, exp=?, lvl=?, m1=?, m2=?, m3=?, m4=?, ability=?, mSel=?, targetType=?, tag=?, item=?, owner=?, pos=?, shiny=?');
                $pokeStmt->bind_param('sisiiiiiiiiisssiiisiiiiiiiiisssii', $id, $pokeNum, $pokeNickname, $pokeExp, $pokeLvl, $pokeM1, $pokeM2, $pokeM3,
                                    $pokeM4, $pokeAbility, $pokeMSel, $pokeTargetType, $pokeTag, $pokeItem, $pokeOwner, $pokePos, $pokeShiny,
                                    $pokeNum, $pokeNickname, $pokeExp, $pokeLvl, $pokeM1, $pokeM2, $pokeM3, $pokeM4, $pokeAbility, $pokeMSel, $pokeTargetType,
                                    $pokeTag, $pokeItem, $pokeOwner, $pokePos, $pokeShiny);
                $pokeStmt->execute();

                //echo $id . $pokeNum . $pokeNickname . $pokeExp . $pokeLvl . $pokeM1 . $pokeM2 . $pokeM3 . $pokeM4 . $pokeAbility
                //    . $pokeMSel . $pokeTargetType . $pokeTag . $pokeItem . $pokeOwner . $pokePos . $pokeShiny;
            }
        }

        $savesStmt->close();
        $pokeStmt->close();
    }

    public function newGame($account, $whichProfile) {
        $conn = $this->conn;
        // Possibly put functionality from createAccount for new save into function so both can reference it
        // Resetting save
        $stmt = $conn->prepare('UPDATE saves SET advanced = ?, advanced_a = ?, nickname = ?, badges = ?, avatar = ?, classic = ?, classic_a = ?, challenge = ?, money = ?, npcTrade = ?, shinyHunt = ?, version = ?, items = ? WHERE id = ?');

        $save = $account -> saves[$whichProfile];
        $id = $account->email . ',' . $whichProfile;
        $items = serialize($save->items);

        $stmt->bind_param('siisisisiiiiis', $save->advanced, $save->advanced_a, $save->nickname, $save->badges, $save->avatar, $save->classic, $save->classic_a, $save->challenge, $save->money, $save->npcTrade, $save->shinyHunt, $save->version, $items, $id);
        $stmt->execute();

        $stmt->close();
        //

        // Removing all pokemon associated with save
        $stmt = $conn->prepare('DELETE FROM pokes WHERE id LIKE ?');

        $id = $account->email . ',' . $whichProfile . ',%';

        $stmt->bind_param('s', $id);
        $stmt->execute();

        $stmt->close();
    }
}