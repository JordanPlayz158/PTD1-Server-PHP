<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');

    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    $action = $_POST['Action'];
    $email = $_POST['Email'];
    switch ($action) {
        case 'checkAccount':
            $achievements = getAccountDataByEmail($conn, 'achievements', $email);

            if($achievements) {
                $achievements = $achievements[0];
                response("Result", "Success");
                response("Reason", "GetAchive");

                //need to respond with Ach1-Ach14
                for($i = 1; $i < 15; $i++) {
                    response("Ach$i", $achievements[intNumberToString($i)]);
                }

                logMySQL($conn);
            } else {
                response("Result", "Failure");
                response("Reason", "NotFound");
            }

            exit(getResponse());
        case 'updateAccount':
            /*
             * type = achievement to be updated
             * pos = char (position) to be updated (in string) (-1 for all chars)
             */
            $achievements = getAccountDataByEmail($conn, 'achievements', $email);

            if($achievements) {
                $num = intNumberToString($_POST['type']);
                $achievements = $achievements[0];
                $status = $achievements[$num];

                $pos = $_POST['pos'];
                if($pos == -1) {
                    $status = 1;
                } else {
                    $split = str_split($status);
                    for($i = 0; $i < sizeof($split); $i++) {
                        // Need to find out if $pos count starts at 1 or 0
                        if($i == $pos) {
                            $split[$i] = '1';
                            break;
                        }
                    }

                    $status = implode($split);
                }

                $stmt = $conn->prepare("UPDATE achievements SET " . $num . " = ? WHERE email = ?");

                if(!$stmt) {
                    echo $conn->errno . " " . $conn->error;
                    response("Result", "Failure");
                    exit(getResponse());
                }

                $types = 'is';

                if($num === 'one') {
                    $types = 'ss';
                }
                $bind = $stmt->bind_param($types, $status, $email);

                if(!$bind) {
                    echo $conn->errno . " " . $conn->error;
                    response("Result", "Failure");
                    exit(getResponse());
                }

                $execute = $stmt->execute();

                if(!$execute) {
                    echo $conn->errno . " " . $conn->error;
                    response("Result", "Failure");
                    exit(getResponse());
                }


                response("Result", "Success");

                logMySQL($conn);
            } else {
                response("Result", "Failure");
                exit(getResponse());
            }
    }

    if (str_starts_with($action, 'get_Reward_')) {
        $achievements = getAccountDataByEmail($conn, 'achievements', $_POST['Email']);

        if($achievements) {
            $achievements = $achievements[0];
            $number = strtolower(substr($action, 11));

            foreach (str_split($achievements[$number]) as $num) {
                if($num == 0) {
                    response("Result", "Failure");
                    response("Reason", "NoReward");
                    exit(getResponse());
                }
            }

            response("Result", "Success");
            response("Reason", "getPrize" . stringNumberToInt($number));
        } else {
            response("Result", "Failure");
            response("Reason", "NotFound");
        }
        exit(getResponse());
    }

    echo getResponse();
} else {
    echo "Invalid Request Method";
}

function intNumberToString(int $num) : string {
    return match ($num) {
        1 => "one",
        2 => "two",
        3 => "three",
        4 => "four",
        5 => "five",
        6 => "six",
        7 => "seven",
        8 => "eight",
        9 => "nine",
        10 => "ten",
        11 => "eleven",
        12 => "twelve",
        13 => "thirteen",
        14 => "fourteen"
    };
}

function stringNumberToInt(string $num) : int {
    return match ($num) {
        "one" => 1,
        "two" => 2,
        "three" => 3,
        "four" => 4,
        "five" => 5,
        "six" => 6,
        "seven" => 7,
        "eight" => 8,
        "nine" => 9,
        "ten" => 10,
        "eleven" => 11,
        "twelve" => 12,
        "thirteen" => 13,
        "fourteen" => 14
    };
}