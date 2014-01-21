<?php

// Weibo
$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
$code_url = $o->getAuthorizeURL(WB_CALLBACK_URL);

// Renren
$rennClient = new RennClient ( RENREN_APP_KEY, RENREN_APP_SECRET);
$state = uniqid ( 'renren_', true );
Yii::app()->session["renren_state"] = $state;
$renren_code_url = $rennClient->getAuthorizeURL (RENREN_CALLBACK_URL, 'code', $state);

// tencent
$tencent_code_url = OAuth::getAuthorizeURL(TENCENT_CALLBACK);

?>

<p><a href="<?php echo $code_url?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>sinasdk/weibo_login.png" title="点击进入授权页面" alt="点击进入授权页面" border="0" /></a></p>
<p><a href="<?php echo $renren_code_url?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>renrensdk/renren.png" title="点击进入授权页面" alt="点击进入授权页面" border="0" /></a></p>
<p><a href="<?php echo $tencent_code_url?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>tencentsdk/logo.png" title="点击进入授权页面" alt="点击进入授权页面" border="0" /></a></p>

<form name="login" id="login" action="<?php echo Yii::app()->createUrl("user/login")?>" method="post" enctype="multipart/form-data">
    <div><label for="">Email: </label><input type="text" value="" name="email"/></div>
    <div><label for="">Password:</label><input type="text" value="" name="password" /></div>
    <div><button>Login</button></div>
</form>

<script type="text/javascript">
    (function ($){
        $(function () {
            $("#login").ajaxForm({
                success: function (res) {
                    console.log(res);
                }
            });
        });
    })(jQuery);
    </script>