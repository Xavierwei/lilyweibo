<?php
//新浪微博
$config['sina_weibo'] = array(
    'site'    => 'sina',
    'appid'     => '27031883',
    'appkey'    => 'ec7c9b399b7d811f1b5f93dc449be341',
    'callback'  => 'user/sina_weibo_oauth/callback',
	'setcallback' => '/user/sns/?t=sina&act=callback',
);