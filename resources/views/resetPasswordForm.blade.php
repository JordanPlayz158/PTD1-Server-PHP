<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='../../_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='../../_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="../../_static/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
    </script>
    <!--<script src="/_static/js/base.js"></script>-->
    <script src="/_static/js/utils.js"></script>
    <script>
        $(function () {
            $('#header').load('/_static/html/header.html');
            $("#nav").load('/_static/html/nav.html');
        });

        function sendPasswordResetForm(event) {
            $("#submitButton").prop('disabled', true);
            event.preventDefault();

            jsonFetch(event).then(response => {
                console.log(response);

                if (!successCheck(response)) {
                    $("#submitButton").prop('disabled', false);
                }
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
                    <div id="result" class=""></div>
                    <div class="title">
                        <p>Forgot Password</p>
                    </div>
                    <div class="content">
                        <p>Please enter your email below to get an email to reset your password!</p>
                        <form id="forgotPassword" action="/forgot-password" method="POST" onsubmit="sendPasswordResetForm(event)">
                            <label><b>Email:</b>
                                <input id="email" class="text" name="email" type="text">
                            </label>
                            @csrf
                            <div class="login_actions">
                                <input id="submitButton" value="Send" type="submit" class="login_btn">
                            </div>
                        </form>
                    </div>
                </div>
            </td>
            <div id="profileResult"></div>
            <td id="main">
                <div class="block">
                    <div class="title">
                        <p>Pokémon Tower Defense: Forgot Password</p>
                    </div>
                    <div class="content">
                        <p>So you forgot your password, it happens to the best of us, but you're in luck, if you used an authentic email address (that you have access to) when making your account then you can reset your password with the link sent to your email!</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
