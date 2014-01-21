<?php
session_start();

header("Content-Type: text/html;charset=utf-8");
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );
$callback_parse = parse_url(WB_CALLBACK_URL);

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
$tmpUser = NULL;
$error = NULL;

// Callback from Sina.
if (isset($_REQUEST['code'])) {
    // 已经从Sina 获取的access token, 我们就从session 获取
    if (isset($_SESSION["token"])) {
        $token = json_decode($_SESSION["token"], TRUE);
        $tmpUser = json_decode($_SESSION["yii_sina_tmpuser"], TRUE);
    }
    // 如果没有access token, 我们就调用一次Sina接口
    else {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys);
                $_SESSION["token"] = json_encode($token);
                //POST token to Yii framework
                $token["r"] = "user/sinacallback";
                $ret = $o->get("http://".$callback_parse["host"], $token);
                if (!$ret["error"]) {
                    ob_clean();
                    $tmpUser = $ret["data"];
                    $_SESSION["yii_sina_tmpuser"] = json_encode($tmpUser);
                }
                else {
                    // Error happend, todo
                    $tmpUser = NULL;
                    $error = $ret["error"]["message"];
                }
	} catch (OAuthException $e) {
            // Error
            $error = $e->getMessage();
	}
    }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Register</title>
    <script type="text/javascript" src="../js/jquery.min.js" ></script>
    <script type="text/javascript" src="../js/jquery.form.js" ></script>
    <script type="text/javascript">
        $("#registerForm").ajaxForm({
            success: function (res) {
                console.log(res);
            }
        });
    </script>
    </head>
    <body>
        <?php if ($error):?>
            Error: <?php echo $error;?>
        <?php else:?>
        <form action="/index.php?r=user/register" method="POST" id="registerForm">
            <div><label for="">nick name</label>
                <input type="text" name="nickname" value="<?php echo $tmpUser["nickname"]?>"/>
            </div>
            <div><label for="">Password</label>
                <input type="text" name="password"/>
            </div>
            <div><label for="">Email</label>
                <input type="text" name="email"/>
            </div> 
            <div><label for="">Tel</label>
                <input type="text" name="tel"/>
            </div>
            <div><label for="">Avadar</label>
                <input type="file" name="avadar"/>
            </div>
            <div><input type="submit" name="submit" /></div>
            <input type="hidden" name="weibo_auth_code" value="<?php echo $tmpUser["weibo_auth_code"]?>"/>
            <input type="hidden" name="weibo_name" value="<?php echo $tmpUser["nickname"]?>"/>
        </form>
        <?php endif?>
    </body>
</html>