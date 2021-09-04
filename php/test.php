<?php
$post_data = array();

foreach(explode("&", file_get_contents('php://input')) as $value) {
    $keyAndValue = explode("=", $value);

    $post_data[$keyAndValue[0]] = $keyAndValue[1];
}

$currentSave = $post_data['CurrentSave'];
$trainerID = $post_data['TrainerID'];

echo exec("java16 -jar ../PTD1-Keygen-1.0-SNAPSHOT.jar " . $currentSave . " " . $trainerID . " true");
?>