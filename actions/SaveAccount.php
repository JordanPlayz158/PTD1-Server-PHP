<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

class SaveAccount {
    function __construct($accounts, $post_data) {
        $account = Utils::getAccount($accounts, $post_data);

        if($account == null) {
            Utils::response("Result", "Failure");
            Utils::response("Reason", "NotFound");

            echo Utils::getResponse();
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
            // Set Items and Poke to blank arrays
            $saves[$whichProfile] = new Save();
            $account -> save = $saves;
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
            $poke = Utils::getPokeByID($pokes, $post_data[$pokeNum . 'myID']);
            $pokeExisted = isset($poke -> myID) ? true : false;

            if(!$pokeExisted || $poke -> myID == 0) {
                $poke -> myID = Utils::generateUniquePokeID($pokes);
            }
                
            $poke -> reason = $post_data[$pokeNum . 'reason'];

            Utils::setPokeData($post_data, $poke, $pokeNum . 'num', "num");
            Utils::setPokeData($post_data, $poke, $pokeNum . 'nickname', "nickname");
            Utils::setPokeData($post_data, $poke, $pokeNum . 'exp', "exp");
            Utils::setPokeData($post_data, $poke, $pokeNum . 'lvl', "lvl");

            for($m = 1; $m < 5; $m++) {
                Utils::setPokeData($post_data, $poke, $pokeNum . 'm' . $m, "m" . $m);
            }

            Utils::setPokeData($post_data, $poke, $pokeNum . 'ability', "ability");
            Utils::setPokeData($post_data, $poke, $pokeNum . 'mSel', "mSel");
            Utils::setPokeData($post_data, $poke, $pokeNum . 'targetType', "targetType");
            Utils::setPokeData($post_data, $poke, $pokeNum . 'tag', "tag");
            Utils::setPokeData($post_data, $poke, $pokeNum . 'item', "item");
            Utils::setPokeData($post_data, $poke, $pokeNum . 'owner', "owner");
            Utils::setPokeData($post_data, $poke, $pokeNum . 'pos', "pos");
            Utils::setPokeData($post_data, $poke, $pokeNum . 'extra', "shiny");

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

        Utils::saveData($accounts);
        new LoadAccount($accounts, $post_data);
    }
}
?>