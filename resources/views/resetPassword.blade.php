<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
    </script>
    <script src="/_static/js/utils.js"></script>
    <script>
        $(function () {
            $("#header").load("/_static/html/header.html");
            $("#nav").load("/_static/html/nav.html");
        });

        const token = window.location.href.substring(window.location.href.lastIndexOf('/') + 1, window.location.href.lastIndexOf('?'));

        function resetPassword(event) {
            var button = $("#submitButton");
            button.prop('disabled', true);
            event.preventDefault();

            const newPass = $('#password').val();

            if(newPass !== $('#password_confirmation').val()) {
                alert("New Password and Confirmed Password do not match!");
                button.prop('disabled', false);
                return;
            }

            document.getElementById('token').value = token;
            document.getElementById('email').value = new URLSearchParams(window.location.search).get('email');

            jsonFetch(event).then(response => {
                console.log(response);

                if(response.ok) {
                    window.location = '/login';
                }

                if(!successCheck(response)) {
                    button.prop('disabled', false);
                }
            });
        }
    </script>
</head>
<body>
<div id="header">
    <noscript>
        <a href="/games/ptd/trading.html">
            <img src="/_static/images/logo.png" alt="Logo">
        </a>
    </noscript>
</div>
<div id="content">
    <div id="nav">
        <noscript>
            <div id="suckerfish">
                <ul class="menu">
                    <li><a href="/">Blog</a></li>
                    <li><a href="/games/ptd/checkPokemon.php?live=true">Home</a></li>
                    <li class="expanded"><a href="/games/ptd/createTrade.php">Account</a>
                        <ul class="menu">
                            <li><a href="/games/ptd/changeNickname.html">Change Nickname</a></li>
                            <li><a href="/games/ptd/changeAvatar.php">Change Avatar</a></li>
                            <li><a href="/games/ptd/reset_password_form.html">Change Password</a></li>
                        </ul>
                    </li>
                    <li><a href="/games/ptd/adoption.php">Pokemon Adoption</a></li>
                    <li><a href="/games/ptd/avatarStore.php">Avatar Store</a></li>
                    <li><a href="/games/ptd/dailyCode.php">Daily Gift</a></li>
                    <li class="expanded"><a href="/games/ptd/inventory.php">Inventory</a>
                        <ul class="menu">
                            <li><a href="/games/ptd/inventory_items.php">Items</a></li>
                            <li><a href="/games/ptd/inventory_avatar.php">Avatars</a></li>
                        </ul>
                    </li>
                    <li><a href="/games/ptd/gameCorner_test.php">Game Corner</a></li>
                    <li class="expanded"><a href="/games/ptd/createTrade.html">Trading Center</a>
                        <ul class="menu">
                            <li><a href="/games/ptd/createTrade.html">Create Trade</a></li>
                            <li><a href="/games/ptd/tradeRequests.html">Your Trade Request</a></li>
                            <li><a href="/games/ptd/searchTrades.php">Search Trades</a></li>
                            <li><a href="/games/ptd/latestTrades.html">Latest Trades</a></li>
                        </ul>
                    </li>
                    <li class="expanded"><a href="/games/ptd/createTrade.php">Utilities</a>
                        <ul class="menu">
                            <li><a href="/games/ptd/transferTo2.php">Transfer to PTD 2</a></li>
                            <li><a href="/games/ptd/removeHack.php">Remove Hacked Tag</a></li>
                            <li><a href="/games/ptd/elite4fix.php">Elite 4 Black Screen Fix</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </noscript>
    </div>
    <table id="content_table">
        <tbody>
        <tr>
            <td id="sidebar">
                <div class="block">
                    <div id="profileResult"></div>
                    <div class="title">
                        <p>Reset Password</p>
                    </div>
                    <div class="content">
                        <p>Please enter your new password and re-type it and hit submit to set your password to the new password!</p>
                        <form action="/reset-password/" method="POST" onsubmit="resetPassword(event)" autocomplete="off">
                            <label><b>New Password:</b>
                                <input id="password" class="text" name="password" type="password" maxlength="10">
                            </label>
                            <label><b>Confirm Password:</b>
                                <input id="password_confirmation" class="text" name="password_confirmation" type="password" maxlength="10">
                            </label>
                            <input id="email" class="text" name="email" type="hidden">
                            <input id="token" class="text" name="token" type="hidden">
                            @csrf
                            <div class="login_actions">
                                <input id="submitButton" value="Reset" type="submit" class="login_btn">
                            </div>
                        </form>
                    </div>
                </div>
            </td>
            <td id="main">
                <div class="block">
                    <div class="title">
                        <p>Pokémon Tower Defense: Reset Password</p>
                    </div>
                    <div class="content">
                        <p>Now that you've opened your custom email link, now it's time to reset your password, so you can re-access your account!</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
