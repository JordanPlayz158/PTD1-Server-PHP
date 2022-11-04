<!DOCTYPE html>
<html lang="en">
<head>
    <title>Resend Verification Email</title>
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

        function resendVerificationEmail(event) {
            $("#submitButton").prop('disabled', true);
            event.preventDefault();

            jsonFetch(event).then(response => {
                console.log(response);

                if(!successCheck(response)) {
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
                        <p>Resend Verification Email</p>
                    </div>
                    <div class="content">
                        <p>Please click the button below to get an email to verify your email!</p>
                        <form id="resendVerificationEmail" action="/email/verification-notification/" method="POST" onsubmit="resendVerificationEmail(event)">
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
                        <p>Pokémon Tower Defense: Resend Verification Email</p>
                    </div>
                    <div class="content">
                        <p>It is currently not required but recommended to verify your email as in the future it will be required, if your email is not correct or not a valid email, it is recommended to change it at [INSERT HYPERLINK TO CHANGE EMAIL PAGE WHEN CREATED]</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
