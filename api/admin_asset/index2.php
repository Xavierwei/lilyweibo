<?php
$url = 'http://lily.local:8888/lilyweibo/api/index.php/admin/isadmin';


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
// 发送cookie
$cookie = "PHPSESSID=f299f07ad80c54d74d7f661eda91aaaa;";
curl_setopt($req, CURLOPT_COOKIE, $cookie);

$output = curl_exec($ch);

print_r($output);

?>