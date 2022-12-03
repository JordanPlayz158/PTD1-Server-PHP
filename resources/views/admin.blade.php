<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Control Panel</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
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
                            <input id="submitButton" value="Login as User!" type="submit" class="login_btn">
                        </form>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
