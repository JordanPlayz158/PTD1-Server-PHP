<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

httpsOnly();

if(session_start()) {
    if (isset($_SESSION['token'])) {
        $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');
        $pass = $config['pass'];

        if (!empty($pass) && $pass == $_SESSION['token']) {
            if (strlen($config['timezone']) > 0) {
                date_default_timezone_set($config['timezone']);
            }
        } else {
            return;
        }
    } else {
        header('Location: /logging/login.php');
    }
} else {
    return;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Logging</title>
        <link rel='stylesheet' type='text/css' href='logging.css'>
        <script type='text/javascript' src='logging.js'></script>
    </head>
<body>
    <?php
        require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
        global $config;

        $mysql = new MySQL($config);

        $result = $mysql->conn->query('SELECT * FROM logs');
        $arr = array_reverse($result -> fetch_all(MYSQLI_ASSOC));
        echo '<h1>You have ' . count($arr) . ' log entries!</h1>';
    ?>
    <input type='checkbox' id='privacy' name='privacy' value='on' checked>
    <label for='privacy'>Privacy Mode!</label>
    <table align='center' border=2>
        <tbody>
            <tr>
                <th colspan=4>
                    <h3>Logs</h3>
                </th>
            </tr>
            <?php
                global $arr;
                global $mysql;
                $str = '';

                foreach (array_keys($arr[0]) as $key) {
                    $str .= "                <th>$key</th>\n";
                }
                    
                foreach ($arr as $key => $value) {
                    $str .= "\n            <tr>";
                
                    foreach ($value as $key1 => $value1) {
                        $buttons = "<button id='Pretty'>Pretty</button><button id='Decode'>Decode</button><button id='Original'>Original</button><br>";

                        $value1 = match ($key1) {
                            'time' => '<td>' . date('d/M/Y H:i:  O', $value1),
                            'ip' => "<td id='ip'><p>$value1</p>",
                            'post_data' => "<td class='post_data'>$buttons<p>" . urldecode($value1) . "</p>",
                            default => "<td>$value1",
                        };
            
                        $value1 = "\n                $value1";
            
                        $str .= "$value1</td>";
                    }
            
                    $str .= "\n            </tr>";
                }
                $str .= '</tr>';
            
                echo $str;

                $mysql->conn->close();
            ?>
        </tbody>
    </table>
 </body>
 </html>