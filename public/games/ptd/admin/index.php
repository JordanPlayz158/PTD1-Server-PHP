<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

httpsOnly();

if(session_start()) {
    if (isset($_SESSION['token'])) {
        $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');
        $pass = $config['pass'];

        if (empty($pass) || $pass !== $_SESSION['token']) {
            return;
        }
    } else {
        header('Location: /games/ptd/admin/login.php');
    }
} else {
    return;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
        <link rel='stylesheet' type='text/css' href='admin.css'>
        <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
        <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
        <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
        <script>
            $(function () {
                $("#header").load("../../../_static/html/header.html");
                $("#nav").load("../../../_static/html/nav.html");
            });
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
                    Email:
                    <br>
                   <select id="accounts" class="form-control">
                        <?php
                        require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
                        global $config;

                        $mysql = new MySQL($config);
                        $conn = $mysql->conn;

                        $emails = $conn->query('SELECT email FROM accounts');
                        while(($row = $emails->fetch_row()) != null) {
                            echo "<option value=''>" . $row[0] . "</option>";
                        }

                        $conn->close();
                        ?>
                    </select>
                </div>
            </td>
            <td id="main">
                <div class="content">
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>

    <script type="text/javascript">
        $("#accounts").select2({
            templateResult: formatState
        });
        function formatState (state) {
                return state.text;
        }
    </script>
</div>
</body>
 </html>