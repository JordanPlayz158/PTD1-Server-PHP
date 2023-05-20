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
@include('components.header')
<div id="content">
    @include('components.nav')
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
