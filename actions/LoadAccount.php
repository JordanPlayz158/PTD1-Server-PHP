<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

class LoadAccount {
    function __construct($accounts, $post_data) {
        $account = Utils::getAccount($accounts, $post_data);

        if($account != null) {
            Utils::response("Result", "Success");
            Utils::response("Reason", "LoggedIn");
            Utils::response("CurrentSave", $account -> CurrentSave);
            Utils::response("newSave", $account -> CurrentSave);
            Utils::response("TrainerID", $account -> TrainerID);
            Utils::response("ProfileID", $account -> ProfileID);
            Utils::response("accNickname", $account -> accNickname);
            Utils::response("dex1", $account -> dex1);
            Utils::response("dex1Shiny", $account -> dex1Shiny);
            Utils::response("dex1Shadow", $account -> dex1Shadow);

            $saves = $account -> save;

            for($i = 0; $i < count($saves); $i++) {
                $s = $i + 1;
                $save = $saves[$i];

                Utils::response("Advanced" . $s, $save -> Advanced);
                Utils::response("Advanced" . $s . "_a", $save -> Advanced_a);
                Utils::response("p" . $s . "_numPoke", $save -> p_numPoke);
                Utils::response("Nickname" . $s, $save -> Nickname);
                Utils::response("Badges" . $s, $save -> Badges);
                Utils::response("avatar" . $s, $save -> avatar);
                Utils::response("Classic" . $s, $save -> Classic);
                Utils::response("Classic" . $s . "_a", $save -> Classic_a);
                Utils::response("Challenge" . $s, $save -> Challenge);
                Utils::response("Money" . $s, $save -> Money);
                Utils::response("NPCTrade" . $s, $save -> NPCTrade);
                Utils::response("shinyHunt" . $s, $save -> ShinyHunt);
                Utils::response("p" . $s . "_numItem", $save -> p_numItem);
                Utils::response("Version" . $s, $save -> Version);

                $ii = 1;
                $pokeNum = 'p' . $s . '_poke_' . $ii . "_";

                $pokes = $save -> poke;

                foreach($pokes as $poke) {
                    Utils::response($pokeNum . "nickname", $poke -> nickname);
                    Utils::response($pokeNum . "num", $poke -> num);
                    Utils::response($pokeNum . "lvl", $poke -> lvl);
                    Utils::response($pokeNum . "exp", $poke -> exp);
                    Utils::response($pokeNum . "owner", $poke -> owner);
                    Utils::response($pokeNum . "targetType", $poke -> targetType);
                    Utils::response($pokeNum . "tag", $poke -> tag);
                    Utils::response($pokeNum . "myID", $poke -> myID);
                    Utils::response("newPokePos_" . $poke -> pos, $poke -> myID);
                    Utils::response($pokeNum . "pos", $poke -> pos);
                    //Shiny (boolean)
                    Utils::response($pokeNum . "noWay", $poke -> shiny);
                    //numMoves
                    Utils::response($pokeNum . "m1", $poke -> m1);
                    Utils::response($pokeNum . "m2", $poke -> m2);
                    Utils::response($pokeNum . "m3", $poke -> m3);
                    Utils::response($pokeNum . "m4", $poke -> m4);
                    Utils::response($pokeNum . "mSel", $poke -> mSel);

                    $ii++;
                    $pokeNum = 'p' . $s . '_poke_' . $ii . "_";
                }

                // Items
                $ii = 1;

                foreach($save -> items as $item) {
                    Utils::response("p" . $s . "_item_" . $ii . "_num", $item -> num);
                    $ii++;
                }


                Utils::response("HMI" . $s, $save -> p_numItem);
                Utils::response("p" . $s . "_hs", $save -> p_hs);
            }

            echo Utils::getResponse();
        } else {
            Utils::response("Result", "Failure");
            Utils::response("Reason", "NotFound");

            echo Utils::getResponse();
        }
    }
}
?>