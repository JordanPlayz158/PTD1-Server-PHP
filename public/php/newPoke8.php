<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Save.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Poke.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../objects/Item.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../actions/CreateAccount.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../actions/LoadAccount.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../actions/SaveAccount.php');

new newPoke8();

class newPoke8 {
    public static $accounts = array();
    public static $body;

    function __construct() {
        $post_data = array();

        $body = file_get_contents('php://input');
        $body = urldecode($body);
        $body = str_replace("saveString=", "", $body);
        newPoke8::$body = $body;

        foreach(explode("&", $body) as $urlVariable) {
            $keyAndValue = explode("=", $urlVariable);

            $post_data[$keyAndValue[0]] = $keyAndValue[1];
        }

        Utils::setEmptyFileContents(Utils::getAccountsFile(), "[]");

        // Save the account credentials to json
        $accountsJson = json_decode(file_get_contents(Utils::getAccountsFile()), true);

        foreach($accountsJson as $accountJson) {
            $account = new Account();

            $account -> Email = $accountJson['Email'];
            $account -> Pass = $accountJson['Pass'];
            $account -> CurrentSave = $accountJson['CurrentSave'];
            $account -> TrainerID = $accountJson['TrainerID'];
            $account -> ProfileID = $accountJson['ProfileID'];
            $account -> accNickname = $accountJson['accNickname'];
            $account -> dex1 = $accountJson['dex1'];
            $account -> dex1Shiny = $accountJson['dex1Shiny'];
            $account -> dex1Shadow = $accountJson['dex1Shadow'];

            foreach($accountJson['save'] as $saveJson) {
                $save = new Save();

                $save -> Advanced = $saveJson['Advanced'];
                $save -> Advanced_a = $saveJson['Advanced_a'];
                $save -> p_numPoke = $saveJson['p_numPoke'];
                $save -> p_numItem = $saveJson['p_numItem'];
                $save -> p_hs = $saveJson['p_hs'];
                $save -> Nickname = $saveJson['Nickname'];
                $save -> Badges = $saveJson['Badges'];
                $save -> avatar = $saveJson['avatar'];
                $save -> Classic = $saveJson['Classic'];
                $save -> Classic_a = $saveJson['Classic_a'];
                $save -> Challenge = $saveJson['Challenge'];
                $save -> Money = $saveJson['Money'];
                $save -> NPCTrade = $saveJson['NPCTrade'];
                $save -> ShinyHunt = $saveJson['ShinyHunt'];
                $save -> Version = $saveJson['Version'];
        
                // Pokemon
                foreach($saveJson['poke'] as $pokeJson) {
                    if(isset($post_data['debug'])) {
                        echo(isset($pokeJson['item']) ? "0" : "\n-1 " . $pokeJson['myID'] . "\n");
                    }

                    $poke = new Poke();
            
                    $poke -> reason = $pokeJson['reason'];
                    $poke -> num = $pokeJson['num'];
                    $poke -> nickname = $pokeJson['nickname'];
                    $poke -> exp = $pokeJson['exp'];
                    $poke -> lvl = $pokeJson['lvl'];
                    $poke -> m1 = $pokeJson['m1'];
                    $poke -> m2 = $pokeJson['m2'];
                    $poke -> m3 = $pokeJson['m3'];
                    $poke -> m4 = $pokeJson['m4'];
                    $poke -> ability = $pokeJson['ability'];
                    $poke -> mSel = $pokeJson['mSel'];
                    $poke -> targetType = $pokeJson['targetType'];
                    $poke -> tag = $pokeJson['tag'];
                    $poke -> item = $pokeJson['item'];
                    $poke -> owner = $pokeJson['owner'];
                    $poke -> myID = $pokeJson['myID'];
                    $poke -> pos = $pokeJson['pos'];
                    $poke -> shiny = $pokeJson['shiny'];

                    $save -> poke[] = $poke;
                }

                // Items
                foreach($saveJson['items'] as $itemJson) {
                    $item = new Item();
        
                    $item -> num = $itemJson['num'];
        
                    $save -> items[] = $item;
                }

                $account -> save[] = $save;
            }

            newPoke8::$accounts[] = $account;
        }

        switch($post_data['Action']) {
            case "createAccount":
                new CreateAccount(newPoke8::$accounts, $post_data);
                break;
            case "loadAccount":
                new LoadAccount(newPoke8::$accounts, $post_data);
                break;
            case "saveAccount":
                new SaveAccount(newPoke8::$accounts, $post_data);
                break;
        }

        Utils::log();
    }
}
?>