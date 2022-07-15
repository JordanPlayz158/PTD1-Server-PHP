<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='../../_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='../../_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="../../_static/css/style.css">
    <!--<script type='text/javascript' src='logging.js'></script>-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="/_static/js/utils.js"></script>
    <script>
        $(function () {
            $("#header").load("../../_static/html/header.html");
            $("#nav").load("../../_static/html/nav.html");
        });

        function login(event) {
            event.preventDefault();

            let target = event.target;
            let formData = {};

            for (let i = 0; i < target.length; i++) {
                const element = target.elements[i];
                const elementType = element.getAttribute('type');
                const elementName = element.getAttribute('name');

                switch (elementType) {
                    case 'submit':
                        continue;
                    case 'checkbox':
                        formData[elementName] = element.checked;
                        break
                    default:
                        formData[elementName] = element.value;
                }
            }

            // Default options are marked with *
            fetch(target.getAttribute('action'), {
                method: target.getAttribute('method'),
                //mode: 'cors', // no-cors, *cors, same-origin
                //cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                //credentials: 'include', // include, *same-origin, omit
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                    // 'Content-Type': 'application/x-www-form-urlencoded',
                },
                //redirect: 'follow', // manual, *follow, error
                //referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
                body: JSON.stringify(formData) // body data type must match "Content-Type" header
            }).then(response => {
                if(response.status !== 204) {
                    console.log(response);
                    return;
                }

                location.href = '/games/ptd/account.html';
            });
        }
    </script>
</head>
<body>
<div id="header"></div>
<div id="content">
    <div id="nav"></div>
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
                                    <a href="reset_password_form.html">Lost Password?</a>

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
