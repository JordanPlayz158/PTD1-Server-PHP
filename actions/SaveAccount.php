<?php

use JetBrains\PhpStorm\Pure;

require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

function saveAccount(Account $account, MySQL $mysql, array $saveData) {
    $conn = $mysql->conn;

    // myTID is TrainerID (saveAccount Action)
    // ONLY 1 (not per save) (global)
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

    /*
     * UPDATE CODE:
     * Minimum values are not accounted for, the minimum values the request sends after successful save is
     * dex1
     * dex1Shiny
     * dex1Shadow
     * whichProfile
      * a_story (advanced)
     * a_story_a (advanced_a)
     * c_story (classic)
     * c_story_a (classic_a)
     * Badges
     * Challenge
     * NPCTrade
     * ShinyHunt
     * Money
     * Nickname
     * Version
     * Avatar
     * HMP
     * HMI
     */

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

    // TODO: Check if the id matches the corresponding expected name before saving, if not hacking (You can't change pokemon's name in swf so the id will always have the default nickname)
    for ($i = 1; $i <= intval($saveData['HMP']); $i++) {
        $pokeNum = 'poke' . $i . '_';
        // This is not finding a Pokémon, so it is returning a new one
        $poke = getPokeByID($pokes, intval($saveData[$pokeNum . 'myID']));
        $pokeExisted = isset($poke->myID);

        if (!$pokeExisted || $poke->myID == 0)
            $poke->myID = generateUniquePokeID($pokes);

        $poke->reason = $saveData[$pokeNum . 'reason'];

        $stmt = $conn->prepare('SELECT id FROM pokes WHERE email = ?');
        $stmt->bind_param('s', $account->email);

        $stmt->execute();
        $ids = array();
        $id = "";

        if ($stmt->bind_result($id)) {
            while ($stmt->fetch())
                $ids[] = [$id];
        }

        $stmt->close();

        if (!isset($poke->myID))
            $poke->myID = generateUniqueID($ids);

        // TODO: Don't save Pokémon if on trade list
        setPokeData($saveData, $poke, $pokeNum . 'num', 'num');
        setPokeData($saveData, $poke, $pokeNum . 'nickname', 'nickname');
        setPokeData($saveData, $poke, $pokeNum . 'exp', 'exp');
        setPokeData($saveData, $poke, $pokeNum . 'lvl', 'lvl');

        for ($m = 1; $m <= 4; $m++)
            setPokeData($saveData, $poke, $pokeNum . 'm' . $m, 'm' . $m);

        setPokeData($saveData, $poke, $pokeNum . 'ability', 'ability');
        setPokeData($saveData, $poke, $pokeNum . 'mSel', 'mSel');
        setPokeData($saveData, $poke, $pokeNum . 'targetType', 'targetType');
        setPokeData($saveData, $poke, $pokeNum . 'tag', 'tag');
        setPokeData($saveData, $poke, $pokeNum . 'item', 'item');
        setPokeData($saveData, $poke, $pokeNum . 'owner', 'owner');
        setPokeData($saveData, $poke, $pokeNum . 'pos', 'pos');

        if (isset($saveData[$pokeNum . 'extra']))
            $poke->shiny = getShiny($saveData[$pokeNum . 'extra']);

        setPokeData($saveData, $poke, $pokeNum . 'shiny', 'shiny');

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
            $id = $poke->myID;

            if (in_array($id, $releasePokes)) {
                /*
                if ($poke -> shiny == 1)
                    $save -> p_hs--;
                */
                $save->p_hs -= ($poke->shiny == 1);

                $numInArray[] = $ii;
                $mysql->releasePoke($account->email, $whichProfile, $id);
            }

            $ii++;
        }

        foreach ($numInArray as $num)
            unset($pokes[$num]);
    }

    $save->p_numPoke = count($pokes);
    $save->pokes = $pokes;

    /*
     * We clear the list of items gathered from the db
     * as the swf sends all the items in every save request
     * so if we didn't clear the array, the items would be duplicated by 2 every time the person saves,
     * which could cause something like this....
     * https://cdn.jordanplayz158.xyz/uploads/db645002741a1f21f1787f60199e3a8548e83dc9.png
     * https://cdn.jordanplayz158.xyz/uploads/2df7bb33b7040c9b02f04dc780c50049ce765b7e.png
     * https://cdn.jordanplayz158.xyz/uploads/ad4df5ed9aa2e6a024ddf05e5190b10a55e56293.png
     * https://cdn.jordanplayz158.xyz/uploads/3f52fb0489544ff69f090e3fd8e27152337bbb7f.png (especially this lol)
     * https://cdn.jordanplayz158.xyz/uploads/a81b46ea69dc2b9f0921bcb9bf003e0b1436626d.png
     */
    if (isset($_POST['debug'])) {
        print_r($save->items);
    }
    $save->items = array();
    for ($i = 1; $i <= intval($saveData['HMI']); $i++) {
        $save->items[] = intval($saveData['item' . $i . '_num']);
    }
    if(isset($_POST['debug'])) {
        print_r($save->items);
    }

    $save->p_numItem = intval($saveData['HMI']);

    $mysql->saveAccount($account);

    response('Result', 'Success');
    response('newSave', 10000000000000);

    foreach ($pokes as $poke) {
        response('newPokePos_' . $poke->pos, $poke->myID);
    }
}

#[Pure] function getPokeByID($pokes, $id) : Poke {
    foreach($pokes as $poke) {
        if($poke->myID == $id)
            return $poke;
    }

    return new Poke();
}

// SHOULD NOT EXIST
// Note to self: Please stop being lazy and refactor the code to actually see what values are sent
// rather than this garbage (refer to MySQL function getColumns)
function setPokeData($saveData, Poke $poke, $postKey, $pokeVariable) {
    if(isset($saveData[$postKey]))
        $poke -> $pokeVariable = $saveData[$postKey];
}

function generateUniquePokeID(array $pokes) : int {
    $valid = false;

    $tmp = -1;

    while (!$valid) {
        $tmp = mt_rand(1, 999999);
        $valid = true;

        foreach ($pokes as $poke) {
            if ($tmp == $poke->myID) {
                $valid = false;
                break;
            }
        }
    }

    return $tmp;
}

function generateUniqueID(array $ids) : int {
    $valid = false;

    $tmp = -1;

    while (!$valid) {
        $tmp = mt_rand(1, 999999);
        $valid = true;

        foreach($ids as $id) {
            if ($tmp == $id[0]) {
                $valid = false;
                break;
            }
        }
    }

    return $tmp;
}

function getShiny(string $extra) : int {
    return match ($extra) {
        /* Shiny
         * Geodude & Graveler = 1
         * Magnemite & Magneton = 2
         * Tentacool & Tentacruel = 3
         * Onix = 4
         * Staryu & Starmie = 5
         * Voltorb & Electrode = 6
         * Hitmonlee & Hitmonchan = 153
         * Omanyte & Omastar & Kabuto & Kabutops = 168
         * Missing No. = 182
         * Articuno & Zapdos & Moltres = 854
         * Generic = 151
         */
        '1', '2', '3', '4', '5', '6', '151', '153', '168', '182', '854' => 1,
        /* Shadow
         * Lickitung = 180
         * Articuno & Zapdos & Moltres = 855
         * Generic = 555
         */
        '180', '555', '855' => 2,
        /* Normal
         * Hitmonlee & Hitmonchan = 152
         * Missing No. = 181
         * Mew = 201
         * Omanyte & Omastar & Kabuto & Kabutops = 154
         * Articuno & Zapdos & Moltres = 857
         * Generic = 0
         *
         * Assuming anything else is normal at the moment
         */
        default => 0,
    };
}
