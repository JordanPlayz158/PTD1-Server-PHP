<!DOCTYPE html>
<html lang="en">
<head>
    <title>Change Account Email</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <!--<script src="/_static/js/base.js"></script>-->
    <script src="/_static/js/utils.js"></script>
    <script>
        function changeEmail(event) {
            event.preventDefault();

            jsonFetch(event).then(response => {
                console.log(response);

                if(!successCheck(response)) {
                    alert('Failed.');
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
                    <div id="result" class=""></div>
                    <div class="title">
                        <p>Change Email</p>
                    </div>
                    <div class="content">
                        <p>Enter the new email <b>THAT YOU OWN</b> that you wish to change your account to!</p>
                        <br>
                        <p>Current Email: "{{ $email }}"</p>
                        <br>
                        <form id="changeEmail" action="/api/account" method="POST" onsubmit="changeEmail(event)">
                            <label><b>Email:</b>
                                <input type="email" name="email" class="text">
                            </label>
                            @csrf
                            <div class="login_actions">
                                <input id="submitButton" value="Update!" type="submit" class="login_btn">
                            </div>
                        </form>
                    </div>
                </div>
            </td>
            <td id="main">
                <div class="block">
                    <div class="title">
                        <p>Pokémon Tower Defense: Change Email</p>
                    </div>
                    <div class="content">
                        <p>It is not currently required but recommended to change your email to a real one (or one you own) as in the future it may be required to log in to PTD.</p>
                        <p>NOTE: <b>You get 5 REQUESTS per HOUR</b></p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
