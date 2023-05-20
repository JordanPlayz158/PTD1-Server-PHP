<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Control Panel</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <script src="/_static/js/js.cookie.js"></script>
    <script>
        function toggleXDebug() {
            if(Cookies.get('XDEBUG_TRIGGER') !== undefined) {
                Cookies.remove('XDEBUG_TRIGGER');
                console.log('XDEBUG toggled off');
            } else {
                Cookies.set('XDEBUG_TRIGGER');
                console.log('XDEBUG toggled on');
            }
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
                        <p>Admin Control Panel</p>
                    </div>
                    <div class="content">
                        <p>This is the page to help administrators do their tasks.</p>
                    </div>
                </div>
            </td>
            <td id="main">
                <div class="block">
                    <div class="title">
                        <p>Tools</p>
                    </div>
                    <div class="content">
                        <style>
                            .vr {
                                border-left: 1px solid #000;
                                height: 100%
                            }
                        </style>

                        <table>
                            <tr>
                                <th>
                                    <div>
                                        <h4>User Switcher:</h4>
                                        <form method="post">
                                            <label><b>ID:</b>
                                                <input type="number" name="id">
                                            </label>
                                            <p><b>OR</b></p>
                                            <br><br>
                                            <label><b>Email:</b>
                                                <input type="text" name="email">
                                            </label>
                                            <br>
                                            <input type="hidden" name="action" value="loginAsUser">
                                            @csrf
                                            <input id="submitButton" value="Login as User!" type="submit"
                                                   class="login_btn">
                                        </form>
                                    </div>
                                </th>
                                <th>
                                    <div>
                                        <h4>Enable XDEBUG:</h4>
                                        <button onclick="toggleXDebug()">Toggle</button>
                                    </div>
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
