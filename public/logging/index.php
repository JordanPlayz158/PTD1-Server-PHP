<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

httpsOnly();

if(isset($_GET['debug'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

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
        return;
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

        $count = $mysql->conn->query("SELECT COUNT(*) FROM logs;")->fetch_row()[0];

        if(isset($_GET['offset'])) {
            $offset = $_GET['offset'];

            if($offset < 0) {
                $offset = 0;
            } else if($offset >= $count) {
                $offset = $count - 100;
            }

            $stmt = $mysql->conn->prepare('SELECT * FROM logs ORDER BY time DESC LIMIT ?,100');
            $stmt->bind_param('i', $offset);
            $stmt->execute();

            $meta = $stmt->result_metadata();
            while ($field = $meta->fetch_field()) {
                $params[] = &$row[$field->name];
            }

            $bind = call_user_func_array(array($stmt, 'bind_result'), $params);

            while ($stmt->fetch()) {
                foreach ($row as $key => $val) {
                    $c[$key] = $val;
                }
                $result[] = $c;
            }

            $stmt->close();
        } else {
            $offset = 0;
            $result = $mysql->conn->query('SELECT * FROM logs ORDER BY time DESC LIMIT 0,100')->fetch_all(MYSQLI_ASSOC);
        }
        $next = intval($offset) + 100;

        if($next > $count) {
            $next = $count;
        }

        echo "<h1>You are displaying $offset-$next/$count log entries!</h1>";
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
                global $result;
                global $mysql;
                $str = '';

                foreach (array_keys($result[0]) as $key) {
                    $str .= "                <th>$key</th>\n";
                }
                    
                foreach ($result as $key => $value) {
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
    <?php
    global $offset;
    global $next;
    global $count;

    $previous = intval($offset) - 100;

    if($previous < 0) {
        $previous = 0;
    }

    if($next > $count) {
        $next = $count - 100;
    }

    echo "<button onclick=\"location.href = '/logging/?offset=$previous';\">Previous</button>";

    echo "<button onclick=\"location.href = '/logging/?offset=$next';\">Next</button>";
    ?>
 </body>
 </html>