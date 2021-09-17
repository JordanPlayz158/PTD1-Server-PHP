<!DOCTYPE html>
<html>
    <head>
        <title>Logging</title>
        <link rel="stylesheet" type="text/css" href="logging.css">
        <script type="text/javascript" src="logging.js"></script>
    </head>
<body>
    <table border=2>
        <tbody>
            <tr>
                <th colspan=4>
                    <h3>Logs</h3>
                </th>
            </tr>
            <?php
                require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

                $arr = array_reverse(json_decode(file_get_contents(Utils::getLogFile()), true));
                $str = "";

                foreach (array_keys($arr[0]) as $key) {
                    $str .= "                <th>$key</th>\n";
                }
                    
                foreach ($arr as $key => $value) {
                    $str .= "\n            <tr>";
                
                    foreach ($value as $key1 => $value1) {
                        $buttons = "<button id='Pretty'>Pretty</button><button id='Decode'>Decode</button><button id='Original'>Original</button><br>";
            
                        switch($key1) {
                            case "time":
                                $value1 = "<td>" . date("d/M/Y H:i:s O", $value1);
                                break;
                            case "post_data":
                                $value1 = "<td class='post_data'>" . $buttons . "<p>" . $value1 . "</p>";
                                break;
                            default:
                                $value1 = "<td>" . $value1;
                        }
            
                        $value1 = "\n                " . $value1;
            
                        $str .= $value1 . "</td>";
                    }
            
                    $str .= "\n            </tr>";
                }
                $str .= "</tr>";
            
                echo $str;
            ?>
        </tbody>
    </table>
 </body>
 </html>