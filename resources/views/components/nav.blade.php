<!-- Navbar -->
<div id="nav">
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css?time={{ env('LAST_RESOURCE_EDIT_TIMESTAMP') }}'>
    <div id="suckerfish" class="suckerfish">
        <ul class="menu">
            <li><a href="/">Blog</a></li>
            <!-- Change to proper check pokemon screen once done -->
            <li><a href="/games/ptd/createTrade.php">Home</a></li>
            <li><a href="/games/ptd/adoption.php">Pokemon Adoption</a></li>
            <li><a href="/games/ptd/avatarStore.php">Avatar Store</a></li>
            <li><a href="/games/ptd/dailyGift.php">Daily Gift</a></li>
            <li class="expanded"><a href="/games/ptd/inventory.php">Inventory</a>
                <ul class="menu">
                    <li><a href="/games/ptd/inventory_items.php">Items</a></li>
                    <li><a href="/games/ptd/inventory_avatar.php">Avatars</a></li>
                </ul>
            </li>
            <li><a href="/games/ptd/gameCorner.php">Game Corner</a></li>
            <li class="expanded"><a href="/games/ptd/giveaways.php">Giveaway Center</a>
                <ul class="menu">
                    <li><a href="/games/ptd/createGiveaway.php">Create Giveaway</a></li>
                    <li><a href="/games/ptd/myGiveaways.php">Your Giveaways</a></li>
                    <li><a href="/games/ptd/giveaways.php">Latest Giveaways</a></li>
                </ul>
            </li>
            <li class="expanded"><a href="/games/ptd/createTrade.php">Trading Center</a>
                <ul class="menu">
                    <li><a href="/games/ptd/createTrade.php">Create Trade</a></li>
                    <li><a href="/games/ptd/myTrades.html">Your Trades</a></li>
                    <li><a href="/games/ptd/myOffers.html">Your Offers/Requests</a></li>
                    <li><a href="/games/ptd/searchTrades.php">Search Trades</a></li>
                    <li><a href="/games/ptd/latestTrades.php">Latest Trades</a></li>
                </ul>
            </li>
            <li class="expanded"><a href="/games/ptd/createTrade.php">Utilities</a>
                <ul class="menu">
                    <li><a href="/games/ptd/transferTo2.php">Transfer to PTD 2</a></li>
                    <li><a href="/games/ptd/removeHack.php">Remove Hacked Tag</a></li>
                    <li><a href="/games/ptd/elite4fix.php">Elite 4 Black Screen Fix</a></li>
                </ul>
            </li>
            <li><a href="/logout">Logout</a></li>
        </ul>
    </div>
    <?php
    $user = Auth::user();
    ?>
    @if(isset($user))
        <div class="suckerfish" style="float: right">
            <ul class="menu">
                <li class="expanded">
                    <a href="/games/ptd/account.php" style="text-align: right">{{ $user->name }}
                        @if($user->notifications_count > 0)
                            <span class="badge">{{ $user->notifications_count }}</span>
                        @endif
                        <ul class="menu rightToLeft">
                            <li><a href="/notifications">Notifications</a></li>
                            <li><a href="/games/ptd/changeNickname.html">Change Nickname</a></li>
                            <li><a href="/games/ptd/changeAvatar.html">Change Avatar</a></li>
                            <li><a href="/games/ptd/reset_password_form.php">Change Password</a></li>
                            <li><a href="/apiKeys">API Keys</a></li>
                        </ul>
                    </a>
                </li>
            </ul>
        </div>
    @endif
</div>
