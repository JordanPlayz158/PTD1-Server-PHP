<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

function LoadAccount($account) 
{
    Utils::response('Result', 'Success');
    Utils::response('Reason', 'LoggedIn');
    Utils::response('CurrentSave', 10000000000000);
    Utils::response('newSave', 10000000000000);
    Utils::response('TrainerID', $account->trainerId);
    Utils::response('ProfileID', Utils::generateValidProfileID($account->trainerId));
    Utils::response('accNickname', $account->accNickname);
    Utils::response('dex1', Utils::fillDex($account->dex1));
    Utils::response('dex1Shiny', Utils::fillDex($account->dex1Shiny));
    Utils::response('dex1Shadow', Utils::fillDex($account->dex1Shadow));

    $saves = $account->saves;

    for($it = 0; $it < count($saves); $it++)
    {
        $i = $it + 1;
        $save = $saves[$it];

        Utils::response('Advanced' . $i, $save->advanced);
        Utils::response('Advanced' . $i . '_a', $save->advanced_a);
        Utils::response('p' . $i . '_numPoke', $save->p_numPoke);
        Utils::response('Nickname' . $i, $save->nickname);
        Utils::response('Badges' . $i, $save->badges);
        Utils::response('avatar' . $i, $save->avatar);
        Utils::response('Classic' . $i, $save->classic);
        Utils::response('Classic' . $i . '_a', $save->classic_a);
        Utils::response('Challenge' . $i, $save->challenge);
        Utils::response('Money' . $i, $save->money);
        Utils::response('NPCTrade' . $i, $save->npcTrade);
        Utils::response('shinyHunt' . $i, $save->shinyHunt);
        Utils::response('p' . $i . '_numItem', $save->p_numItem);
        Utils::response('Version' . $i, $save->version);

        $ii = 1;
        $pokeNum = 'p' . $i . '_poke_' . $ii . '_';

        $pokes = $save->pokes;

        foreach ($pokes as $poke) 
        {
            Utils::response($pokeNum . 'nickname', $poke->nickname);
            Utils::response($pokeNum . 'num', $poke->num);
            Utils::response($pokeNum . 'lvl', $poke->lvl);
            Utils::response($pokeNum . 'exp', $poke->exp);
            Utils::response($pokeNum . 'owner', $poke->owner);
            Utils::response($pokeNum . 'targetType', $poke->targetType);
            Utils::response($pokeNum . 'tag', $poke->tag);
            Utils::response($pokeNum . 'myID', $poke->myID);
            Utils::response('newPokePos_' . $poke->pos, $poke->myID);
            Utils::response($pokeNum . 'pos', $poke->pos);

            //Shiny (boolean)
            Utils::response($pokeNum . 'noWay', $poke->shiny);

            //numMoves
            Utils::response($pokeNum . 'm1', $poke->m1);
            Utils::response($pokeNum . 'm2', $poke->m2);
            Utils::response($pokeNum . 'm3', $poke->m3);
            Utils::response($pokeNum . 'm4', $poke->m4);
            Utils::response($pokeNum . 'mSel', $poke->mSel);

            $ii++;
            $pokeNum = 'p' . $i . '_poke_' . $ii . '_';
        }

        // Items
        $ii = 1;

        foreach($save -> items as $item) 
        {
            Utils::response('p' . $i . '_item_' . $ii . '_num', $item -> num);
            $ii++;
        }

        Utils::response('HMI' . $i, $save -> p_numItem);
        Utils::response('p' . $i . '_hs', $save -> p_hs);
    }
}

?>