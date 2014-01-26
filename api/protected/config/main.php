<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'lilyweibo',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.vendors.*',
		'application.helpers.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
// 				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
// 				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
// 				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
	
				//REST routers
				array('scarf/list', 'pattern' => 'scarf/list', 'verb' => 'POST'),
				array('scarf/myrank', 'pattern' => 'scarf/myrank', 'verb' => 'GET'),
				array('scarf/post', 'pattern' => 'scarf/post', 'verb' => 'POST'),
				array('scarf/put', 'pattern' => 'scarf/put', 'verb' => 'POST'),
				array('scarf/dmx', 'pattern' => 'scarf/dmx', 'verb' => 'GET'),
				array('scarf/share', 'pattern' => 'scarf/share', 'verb' => 'POST'),
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=lilyweibo',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		
		//上传图片路径，网址
		'uploadPath' => ROOT_PATH . '/uploads/',
		'uploadUrl' => 'http://www.19youxi.com/uploads/',
		
		//sns配置
		'sina_akey' => '27031883',	//sina AKEY
		'sina_skey' => 'ec7c9b399b7d811f1b5f93dc449be341',	//sina SKEY
		'sina_callback' => 'http://www.19youxi.com/index.php/user/sinacallback',	//sina 回调地址
	),
);