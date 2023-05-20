<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='../../_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='../../_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="../../_static/css/style.css">
    <script type='text/javascript' src='/_static/js/tracking.js'></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="/_static/js/utils.js"></script>
    <script src="/_static/js/js.cookie.js"></script>
    <script>
        Cookies.remove('apiToken');

        function login(event) {
            event.preventDefault();

            jsonFetch(event).then(response => {
                switch (response.status) {
                    case 204:
                        location.href = '/games/ptd/account.php';
                        break;
                    case 422:
                        response.json().then(data => {
                            let result = document.createElement('div');
                            result.id = 'result';
                            result.className = 'error-msg msg';

                            let message = document.createElement('a');
                            message.innerText = data['message'];

                            result.append(message);

                            document.getElementById('main').append(result);
                        })
                        break;
                }

                console.log(response);
            });
        }
    </script>
</head>
<body>
@include('components.header')
<div id="content">
    @include('components.nav')
    <table id="content_table">
        <tbody>
            <tr>
                <td id="sidebar">
                    <div class="block">
                        <div class="title">
                            <p>Log in</p>
                        </div>
                        <div class="content">
                            <form action="/login" method="POST" onsubmit="login(event)" autocomplete="off">
                                <label><b>Email:</b>
                                    <input id="email" class="text" name="email" type="text">
                                </label>
                                <label><b>Password:</b>
                                    <input id="password" name="password" type="password" class="text" maxlength="10">
                                </label>
                                @csrf
                                <label style="float: right"><b>Remember Me?</b>
                                    <input name="remember" type="checkbox">
                                </label>
                                <br><br><br><br>
                                <div class="login_actions">
                                    <a href="forgot-password">Lost Password?</a>

                                    <input value="Login" type="submit" class="login_btn">
                                </div>
                            </form>
                        </div>
                    </div>
                </td>
                <div id="profileResult"></div>
                <td id="main">
                    <div class="block">
                        <div class="title">
                            <p>Pokémon Tower Defense: Pokémon Center</p>
                        </div>
                        <div class="content">
                            <p>In order to use the Pokémon Center you must first log in. After this is done you'll be able to access the trade center and much more.</p>
                            <p>NOTE: Don't play your account while you use the Pokémon Center! It may cause you to lose saved data!</p>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
