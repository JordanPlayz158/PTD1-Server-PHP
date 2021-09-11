<?php
$body = file_get_contents('php://input');
$body = urldecode($body);
$body = str_replace("saveString=", "", $body);
$body = str_replace("&", "\n", $body);

echo $body;
?>