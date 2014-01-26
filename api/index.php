<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

defined('ROOT_PATH') or define('ROOT_PATH', dirname(__FILE__));

// require sinasdk
$sinasdk = dirname(__FILE__)."/sinasdk";
include_once( $sinasdk.'/config.php' );
include_once( $sinasdk.'/saetv2.ex.class.php' );

require_once($yii);
Yii::createWebApplication($config)->run();
