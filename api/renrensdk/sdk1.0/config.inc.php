<?php
/*
 * 总体配置文件，包括API Key, Secret Key，以及所有允许调用的API列表
 * This file for renren_configure all necessary things for invoke, including API Key, Secret Key, and all APIs list
 *
 * @Modified by mike on 17:54 2011/12/21.
 * @Modified by Edison tsai on 16:34 2011/01/13 for remove call_id & session_key in all parameters.
 * @Created: 17:21:04 2010/11/23
 * @Author:	Edison tsai<dnsing@gmail.com>
 * @Blog:	http://www.timescode.com
 * @Link:	http://www.dianboom.com
 */

$renren_config				= new stdClass;

$renren_config->APIURL		= 'http://api.renren.com/restserver.do'; //RenRen网的API调用地址，不需要修改
$renren_config->APIKey		= '9c7436055e5a4f0497312f96a92079ec';	//你的API Key，请自行申请
$renren_config->SecretKey	= '6f495817778f421a91be3dc1c6b4e079';	//你的API 密钥
$renren_config->APIVersion	= '1.0';	//当前API的版本号，不需要修改
$renren_config->decodeFormat	= 'json';	//默认的返回格式，根据实际情况修改，支持：json,xml

define("RENREN_APIKEY", $renren_config->APIKey);
define("RENREN_REDIRECT_URI", "http://garnier.local/index.php?r=user/renrencallback");
define("RENREN_SECRETKEY", $renren_config->SecretKey);

return $renren_config;
?>