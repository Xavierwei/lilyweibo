<?php
$url = 'http://lily.local:8888/lilyweibo/api/index.php/user/isadmin';

$json_data = file_get_contents($url);
$array = json_decode($json_data,true);
echo '<pre>';print_r($array);

?>