<?php
$url = '../api/';
$data = array(
  'api_key'=>'3qQ2Edm62Vd4bAVCwNoxgn0l',
  'method'=>'baidu.zhidao.getQuestionList',
  'call_id'=>'1308713190',
  'cid'=>59533,
  'qstatus'=>1,
  'format'=>'json',
  'page_no'=>1,
  'page_size'=>25,
  'keywords'=>'财务',
  'bd_sig'=>'2bad1c47bb75e0363a689f4b09743afb'
);

$json_data = postData($url, $data);
$array = json_decode($json_data,true);
echo '<pre>';print_r($array);

function postData($url, $data)
{
  $ch = curl_init();
  $timeout = 300;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $handles = curl_exec($ch);
  curl_close($ch);
  return $handles;
}

?>