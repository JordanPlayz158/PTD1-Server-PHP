<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

function saveAccount($account, $mysql, $saveData) {
    $conn = $mysql->conn;

    // myTID is TrainerID (saveAccount Action)
    // ONLY 1 (not per save)
    $account->dex1 = $saveData['dex1'];
    $account->dex1Shiny = $saveData['dex1Shiny'];
    $account->dex1Shadow = $saveData['dex1Shadow'];

    $whichProfile = $saveData['whichProfile'] - 1;

    $saves = $account->saves;

    if (isset($saveData['newGame']) && $saveData['newGame'] == 'yes') {
        // Set Items and Poke to blank arrays
        $num = $saves[$whichProfile]->num;
        $saves[$whichProfile] = new Save();
        $saves[$whichProfile]->num = $num;
        $account->saves = $saves;
        $mysql->newGame($account, $whichProfile);
    }

    $save = $saves[$whichProfile];

    $aStory = 'a_story';
    $save->advanced = intval($saveData[$aStory]);
    
    $aStory = $aStory . '_a';
    $save->advanced_a = intval($saveData[$aStory]);
    
    $cStory = 'c_story';
    $save->classic = intval($saveData[$cStory]);
    
    $cStory = $cStory . '_a';
    $save->classic_a = intval($saveData[$cStory]);
    
    $save->badges = intval($saveData['badges']);
    $save->challenge = intval($saveData['challenge']);
    $save->npcTrade = intval($saveData['NPCTrade']);
    $save->shinyHunt = intval($saveData['ShinyHunt']);
    $save->money = intval($saveData['Money']);
    $save->nickname = $saveData['Nickname'];
    $save->version = intval($saveData['Version']);
    $save->avatar = $saveData['Avatar'];

    $pokes = $save->pokes;
    
    for ($i = 1; $i <= intval($saveData['HMP']); $i++) {
        $pokeNum = 'poke' . $i . '_';
        // This is not finding a pokemon so it is returning a new one
        $poke = Utils::getPokeByID($pokes, intval($saveData[$pokeNum . 'myID']));
        // isset($poke->myID) ? true : false
        // Was changed from above, if issues occur, change back
        $pokeExisted = isset($poke->myID);

        if (!$pokeExisted || $poke->myID == 0)
            $poke->myID = Utils::generateUniquePokeID($pokes);
            
        $poke->reason = $saveData[$pokeNum . 'reason'];

        $email = $account->email . ',%';
        $stmt = $conn->prepare('SELECT email FROM pokes WHERE email LIKE ?');
        $stmt->bind_param('s', $email);

        $stmt->execute();
        $ids = array();
        $id = "";

        if ($stmt->bind_result($id)) {
            while ($stmt->fetch())
                $ids[] = [$id];
        }

        $stmt->close();
        
        if (!isset($poke->id)) 
            $poke->id = $account->email . ',' . $whichProfile . ',' . Utils::generateUniqueID($ids);

        Utils::setPokeData($saveData, $poke, $pokeNum . 'num', 'num');
        Utils::setPokeData($saveData, $poke, $pokeNum . 'nickname', 'nickname');
        Utils::setPokeData($saveData, $poke, $pokeNum . 'exp', 'exp');
        Utils::setPokeData($saveData, $poke, $pokeNum . 'lvl', 'lvl');

        for ($m = 1; $m <= 4; $m++)
            Utils::setPokeData($saveData, $poke, $pokeNum . 'm' . $m, 'm' . $m);

        Utils::setPokeData($saveData, $poke, $pokeNum . 'ability', 'ability');
        Utils::setPokeData($saveData, $poke, $pokeNum . 'mSel', 'mSel');
        Utils::setPokeData($saveData, $poke, $pokeNum . 'targetType', 'targetType');
        Utils::setPokeData($saveData, $poke, $pokeNum . 'tag', 'tag');
        Utils::setPokeData($saveData, $poke, $pokeNum . 'item', 'item');
        Utils::setPokeData($saveData, $poke, $pokeNum . 'owner', 'owner');
        Utils::setPokeData($saveData, $poke, $pokeNum . 'pos', 'pos');

        if (isset($saveData[$pokeNum . 'extra']))
            $poke->shiny = intval(substr($saveData[$pokeNum . 'extra'], 0, 1));

        if (!$pokeExisted) {
            /*
            if($poke->shiny == 1)
                $save->p_hs++;
            */
                $save->p_hs += ($poke->shiny == 1);

            $pokes[] = $poke;
        }
    }

    if (isset($saveData['releasePoke'])) {
        $releasePokes = explode('|', $saveData['releasePoke']);

        $numInArray = array();

        $ii = 0;
        foreach ($pokes as $poke) {
            $id = $poke -> myID;

            if (in_array($id, $releasePokes)) {
                /*
                if ($poke -> shiny == 1)
                    $save -> p_hs--;
                */  
                    $save->p_hs -= ($poke->shiny == 1);

                $numInArray[] = $ii;
            }
        
            $ii++;
        }
        
        foreach ($numInArray as $num)
            unset($pokes[$num]);
    }

    $save->p_numPoke = count($pokes);
    $save->pokes = $pokes;

    $iii = 1;
    while (isset($saveData['item' . $iii . '_num'])) {
        /*
        $item = new Item();
        $item->num = intval($saveData['item' . $iii . '_num']);
        $save -> items[] = $item;
        */
            $itemNum = intval($saveData['item' . $iii . '_num']);
            $save -> items[] = $itemNum;

        $iii++;
    }
    
    $save -> p_numItem = intval($saveData['HMI']);

    //print_r($account);

    Utils::$mysql->saveAccount($account);
    loadAccount($account);
}