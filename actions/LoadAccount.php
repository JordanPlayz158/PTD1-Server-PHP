<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

function loadAccount($account) {
    response('Result', 'Success');
    response('Reason', 'LoggedIn');
    response('CurrentSave', 10000000000000);
    response('newSave', 10000000000000);
    response('TrainerID', 333);
    response('ProfileID', generateProfileId());
    response('accNickname', $account->accNickname);
    response('dex1', fillDex($account->dex1));
    response('dex1Shiny', fillDex($account->dex1Shiny));
    response('dex1Shadow', fillDex($account->dex1Shadow));

    $saves = $account->saves;

    for($i = 0; $i < count($saves); $i++) {
        $saveNum = $i + 1;
        $save = $saves[$i];

        response('Advanced' . $saveNum, $save->advanced);
        response('Advanced' . $saveNum . '_a', $save->advanced_a);
        response('p' . $saveNum . '_numPoke', $save->p_numPoke);
        response('Nickname' . $saveNum, $save->nickname);
        response('Badges' . $saveNum, $save->badges);
        response('avatar' . $saveNum, $save->avatar);
        response('Classic' . $saveNum, $save->classic);
        response('Classic' . $saveNum . '_a', $save->classic_a);
        response('Challenge' . $saveNum, $save->challenge);
        response('Money' . $saveNum, $save->money);
        response('NPCTrade' . $saveNum, $save->npcTrade);
        response('shinyHunt' . $saveNum, $save->shinyHunt);
        response('p' . $saveNum . '_numItem', $save->p_numItem);
        response('Version' . $saveNum, $save->version);

        $pokes = $save->pokes;

        // TODO: Don't give swf Pokémon if on trade list
        for($ii = 0; $ii < count($pokes); $ii++) {
            $pokeNum = $ii + 1;
            $pokePrefix = 'p' . $saveNum . '_poke_' . $pokeNum . '_';
            $poke = $pokes[$ii];

            response($pokePrefix . 'nickname', $poke->nickname);
            response($pokePrefix . 'num', $poke->num);
            response($pokePrefix . 'lvl', $poke->lvl);
            response($pokePrefix . 'exp', $poke->exp);
            response($pokePrefix . 'owner', $poke->owner);
            response($pokePrefix . 'targetType', $poke->targetType);
            response($pokePrefix . 'tag', $poke->tag);
            response($pokePrefix . 'myID', $poke->myID);
            response('newPokePos_' . $poke->pos, $poke->myID);
            response($pokePrefix . 'pos', $poke->pos);

            //Shiny (boolean)
            response($pokePrefix . 'noWay', $poke->shiny);

            //numMoves
            response($pokePrefix . 'm1', $poke->m1);
            response($pokePrefix . 'm2', $poke->m2);
            response($pokePrefix . 'm3', $poke->m3);
            response($pokePrefix . 'm4', $poke->m4);
            response($pokePrefix . 'mSel', $poke->mSel);
        }

        // Items
        if (isset($_POST['debug'])) {
            print_r($save->items);
        }

        $items = $save -> items;
        for($ii = 0; $ii < count($items); $ii++) {
            $itemNum = $ii + 1;
            response('p' . $saveNum . '_item_' . $itemNum . '_num', $items[$ii]);
        }

        response('HMI' . $saveNum, $save -> p_numItem);
        response('p' . $saveNum . '_hs', $save -> p_hs);
    }
}

function generateProfileId() : string {
    /* this keygen is just for a hacker check, so I hardcoded the profileId
       if for whatever reason you want to have random trainer and profileIds then
       I'll leave the Keygen code in just in case (and as we made it look nice and perform well)
       for CurrentSave randomization, you can use something like random_int to generate a number
       to the required size which is 14 character
       //return generateProfileId(10000000000000, $trainerID);

       //generateProfileId(10000000000000, 333); results in the output below */
    return 'ikkg';
}

function fillDex($dex) : string {
    if($dex === null) {
        $dex = '0';
    }

    while (strlen($dex) < 151) 
        $dex .= '0';

    return $dex;
}