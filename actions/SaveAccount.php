<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

function saveAccount($account, $post_data, $conn) {
    // myTID is TrainerID (saveAccount Action)
    // ONLY 1 (not per save)
    $account->dex1 = $post_data['dex1'];
    $account->dex1Shiny = $post_data['dex1Shiny'];
    $account->dex1Shadow = $post_data['dex1Shadow'];

    $whichProfile = $post_data['whichProfile'] - 1;

    $saves = $account->saves;

    if (isset($post_data['newGame']) && $post_data['newGame'] == 'yes') {
        // Set Items and Poke to blank arrays
        $num = $saves[$whichProfile]->num;
        $saves[$whichProfile] = new Save();
        $saves[$whichProfile]->num = $num;
        $account->saves = $saves;
    }

    $save = $saves[$whichProfile];

    $aStory = 'a_story';
    $save->advanced = intval($post_data[$aStory]);
    
    $aStory = $aStory . '_a';
    $save->advanced_a = intval($post_data[$aStory]);
    
    $cStory = 'c_story';
    $save->classic = intval($post_data[$cStory]);
    
    $cStory = $cStory . '_a';
    $save->classic_a = intval($post_data[$cStory]);
    
    $save->badges = intval($post_data['badges']);
    $save->challenge = intval($post_data['challenge']);
    $save->npcTrade = intval($post_data['NPCTrade']);
    $save->shinyHunt = intval($post_data['ShinyHunt']);
    $save->money = intval($post_data['Money']);
    $save->nickname = $post_data['Nickname'];
    $save->version = intval($post_data['Version']);
    $save->avatar = $post_data['Avatar'];

    $pokes = $save->pokes;

    print_r($save->pokes);
    
    for ($i = 1; $i <= intval($post_data['HMP']); $i++) {
        $pokeNum = 'poke' . $i . '_';
        // This is not finding a pokemon so it is returning a new one
        $poke = Utils::getPokeByID($pokes, intval($post_data[$pokeNum . 'myID']));
        // isset($poke->myID) ? true : false
        // Was changed from above, if issues occur, change back
        $pokeExisted = isset($poke->myID);

        if (!$pokeExisted || $poke->myID == 0)
            $poke->myID = Utils::generateUniquePokeID($pokes);
            
        $poke->reason = $post_data[$pokeNum . 'reason'];

        $email = $account->email . ',%';
        $stmt = $conn->prepare('SELECT id FROM pokes WHERE id LIKE ?');
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

        Utils::setPokeData($post_data, $poke, $pokeNum . 'num', 'num');
        Utils::setPokeData($post_data, $poke, $pokeNum . 'nickname', 'nickname');
        Utils::setPokeData($post_data, $poke, $pokeNum . 'exp', 'exp');
        Utils::setPokeData($post_data, $poke, $pokeNum . 'lvl', 'lvl');

        for ($m = 1; $m <= 4; $m++)
            Utils::setPokeData($post_data, $poke, $pokeNum . 'm' . $m, 'm' . $m);

        Utils::setPokeData($post_data, $poke, $pokeNum . 'ability', 'ability');
        Utils::setPokeData($post_data, $poke, $pokeNum . 'mSel', 'mSel');
        Utils::setPokeData($post_data, $poke, $pokeNum . 'targetType', 'targetType');
        Utils::setPokeData($post_data, $poke, $pokeNum . 'tag', 'tag');
        Utils::setPokeData($post_data, $poke, $pokeNum . 'item', 'item');
        Utils::setPokeData($post_data, $poke, $pokeNum . 'owner', 'owner');
        Utils::setPokeData($post_data, $poke, $pokeNum . 'pos', 'pos');

        if (isset($post_data[$pokeNum . 'extra']))
            $poke->shiny = intval(substr($post_data[$pokeNum . 'extra'], 0, 1));

        if (!$pokeExisted) {
            /*
            if($poke->shiny == 1)
                $save->p_hs++;
            */
                $save->p_hs += ($poke->shiny == 1);

            $pokes[] = $poke;
        }
    }

    if (isset($post_data['releasePoke'])) {
        $releasePokes = explode('|', $post_data['releasePoke']);

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
    while (isset($post_data['item' . $iii . '_num'])) {
        /*
        $item = new Item();
        $item->num = intval($post_data['item' . $iii . '_num']);
        $save -> items[] = $item;
        */
            $itemNum = intval($post_data['item' . $iii . '_num']);
            $save -> items[] = $itemNum;

        $iii++;
    }
    
    $save -> p_numItem = intval($post_data['HMI']);

    //print_r($account);

    Utils::$mysql->saveAccount($account);
    loadAccount($account);
}