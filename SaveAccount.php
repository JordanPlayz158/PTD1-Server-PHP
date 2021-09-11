<?php
class SaveAccount {
    function __constructor() {
        $account = getAccount();

        if($account == null) {
            response("Result", "Failure");
            response("Reason", "NotFound");

            echo $response;
            return;
        }

        $account -> CurrentSave = $post_data['currentSave'];
        $account -> TrainerID = $post_data['myTID'];
        $account -> ProfileID = $post_data['myVID'];
        $account -> dex1 = $post_data['dex1'];
        $account -> dex1Shiny = $post_data['dex1Shiny'];
        $account -> dex1Shadow = $post_data['dex1Shadow'];

        $whichProfile = $post_data['whichProfile'] - 1;

        $saves = $account -> save;

        if(isset($post_data['newGame']) && $post_data['newGame'] == "yes") {
            $saves[$whichProfile] = new Save();
            $account -> save = $saves;
            saveData();
            loadAccount();
            return;
        }

        $save = $saves[$whichProfile];

        $aStory = "a_story";
        $cStory = "c_story";

        $save -> Badges = $post_data['badges'];
        $save -> Challenge = $post_data['challenge'];
        $save -> Advanced = $post_data[$aStory];

        $aStory = $aStory . "_a";

        $save -> Advanced_a = $post_data[$aStory];
        $save -> Classic = $post_data[$cStory];

        $cStory = $cStory . "_a";

        $save -> Classic_a = $post_data[$cStory];
        $save -> NPCTrade = $post_data['NPCTrade'];
        $save -> ShinyHunt = $post_data['ShinyHunt'];
        $save -> Money = $post_data['Money'];
        $save -> Nickname = $post_data['Nickname'];
        $save -> Version = $post_data['Version'];
        $save -> avatar = $post_data['Avatar'];

        $pokes = $save -> poke;

        
        for($i = 1; $i <= $post_data['HMP']; $i++) {
            $pokeNum = 'poke' . $i . "_";
            $poke = getPokeByID($pokes, $post_data[$pokeNum . 'myID']);
            $pokeExisted = isset($poke -> myID) ? true : false;

            if(!$pokeExisted || $poke -> myID == 0) {
                $poke -> myID = generateUniquePokeID($pokes);
            }
                
            $poke -> reason = $post_data[$pokeNum . 'reason'];

            setPokeData($poke, $pokeNum . 'num', "num");
            setPokeData($poke, $pokeNum . 'nickname', "nickname");
            setPokeData($poke, $pokeNum . 'exp', "exp");
            setPokeData($poke, $pokeNum . 'lvl', "lvl");

            for($m = 1; $m < 5; $m++) {
                setPokeData($poke, $pokeNum . 'm' . $m, "m" . $m);
            }

            setPokeData($poke, $pokeNum . 'ability', "ability");
            setPokeData($poke, $pokeNum . 'mSel', "mSel");
            setPokeData($poke, $pokeNum . 'targetType', "targetType");
            setPokeData($poke, $pokeNum . 'tag', "tag");
            setPokeData($poke, $pokeNum . 'item', "item");
            setPokeData($poke, $pokeNum . 'owner', "owner");
            setPokeData($poke, $pokeNum . 'pos', "pos");
            setPokeData($poke, $pokeNum . 'extra', "shiny");

            if(!$pokeExisted) {
                if($poke -> shiny == "1") {
                    $save -> p_hs++;
                }
                $pokes[] = $poke;
            }
        }

        $save -> p_numPoke = count($pokes);

        if(isset($post_data['releasePoke'])) {
            $releasePokes = explode("|", $post_data['releasePoke']);

            $numInArray = array();

            $ii = 0;
            foreach($pokes as $poke) {
                $id = $poke -> myID;

                if(in_array($id, $releasePokes)) {
                    if($poke -> shiny == "1") {
                        $save -> p_hs--;
                    }
                    $numInArray[] = $ii;
                }
            
                $ii++;
            }
            
            foreach($numInArray as $num) {
                unset($pokes[$num]);
            }
        }

        $save -> poke = $pokes;

        $iii = 1;
        while(isset($post_data['item' . $iii . '_num'])) {
            $item = new Item();

            $item -> num = $post_data['item' . $iii . '_num'];

            $save -> items[] = $item;

            $iii++;
        }
        
        $save -> p_numItem = $post_data['HMI'];

        saveData();
        loadAccount();
    }



}