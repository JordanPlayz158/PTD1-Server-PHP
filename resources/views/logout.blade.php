<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <script type='text/javascript' src='/_static/js/tracking.js'></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="/_static/js/utils.js"></script>
    <script>
        $(function () {
            $("#header").load("../../_static/html/header.html");
            $("#nav").load("../../_static/html/nav.html");
        });

        function logout(event) {
            event.preventDefault();

            jsonFetch(event).then(response => {
                if(response.status !== 204) {
                    console.log(response);
                    return;
                }

                location.href = '/login';
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
                            <p style="text-align: center">Logout</p>
                        </div>
                        <div class="content">
                            <form action="/logout" method="POST" onsubmit="logout(event)" autocomplete="off" style="text-align: center">
                                @csrf
                                    <input value="Logout" type="submit" class="login_btn">
                            </form>
                        </div>
                    </div>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
