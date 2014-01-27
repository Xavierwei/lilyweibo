<?php

class UserController extends Controller
{
	public $defaultAction = 'login';
	protected $request = NULL;
	
	public function init() {
		parent::init();
		$this->request = Yii::app()->getRequest();
	}
	
    public function returnJSON($data) {
        header("Content-Type: application/json");
        echo CJSON::encode($data);
        Yii::app()->end();
    }
	
    public function error($msg, $code) {
        return array(
            "data" => NULL,
            "error" => array(
                "code" => $code,
                "message" => $msg,
            ),
        );
    }
	
    public static function getLoginUser() {
        return Yii::app()->session["user"];
    }

    public static function isLogin() {
        return Yii::app()->session["is_login"] == "true";
    }


  public function actionIsAdmin() {
    return $this->returnJSON(array(
      "data" => true,
      "error" => NULL
    ));
  }

	/**
	 * 登陆
	 */
	public function actionLogin() {
        $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
        $weiboUrl = $o->getAuthorizeURL(WB_CALLBACK_URL);
		$this->render('login', array(
			'weiboUrl' => $weiboUrl
		));
	}

	/**
	 * 退出
	 */
	public function actionLogout() {
		Yii::app()->session->clear();
		Yii::app()->session->destroy();

		return $this->returnJSON(array(
			"data" => "logout success",
			"error" => NULL
		));
	}


	/**
	 * 取得好友列表
	 */
	public function actionGetFriendList() {
		if(self::isLogin()) {
			$user = $this->getLoginUser();
			$userModel = new User();
			$friendList = $userModel->getUserFriend($user['sns_uid']);
			return $this->returnJSON(array(
				"data" => $friendList,
				"error" => NULL
			));
		}
		else {
			$this->returnJSON($this->error('not login', 1001));
		}
	}

	/**
	 * 邀请好友
	 */
	public function actionInvitFriends() {
		if(self::isLogin()) {
			$userModel = new User();
			$userModel->invitFriend('1856415417');
		}
		else {
			$this->returnJSON($this->error('not login', 1001));
		}
	}

	/**
	 * Sina Callback
	 */
	public function actionSinacallback() {
		$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);   
		if ($code = $this->request->getQuery('code')) {
            $keys = array();
            $keys['code'] = $code;
            $keys['redirect_uri'] = WB_CALLBACK_URL;
			try {
				$token = $o->getAccessToken('code', $keys);
			} catch(oAuthException $e) {
				$this->returnJSON($this->error('request error', 1001));
			}
			$access_token = Yii::app()->session["weibo_access_token"] = $token["access_token"];
			$c = new SaeTClientV2(WB_AKEY, WB_SKEY, $access_token);
			$basic_account = $c->show_user_by_id($token["uid"]);
			if ($user = $this->getUser($basic_account['id'])) {
				$this->refreshSnsUser($user['uid'], $access_token, $basic_account);
				Yii::app()->session['is_login'] = "true";
				Yii::app()->session['user'] = $user;
				//$this->returnJSON($user);
			} else {  // 自动注册
				if ($user = $this->regUser($basic_account)) {
					Yii::app()->session["is_login"] = "true";
					Yii::app()->session["user"] = $user;
					//$this->returnJSON($newUser);
				} else {
					//$this->returnJSON($this->error("login error", 1001));
				} 
			}
      $this->redirect('../../../logined.html');
		} else {
			$this->returnJSON($this->error("login error", 1001));
		}
	}
	
    public function getUser($sns_uid) {
		$user = Yii::app()->db->createCommand()
			->select("*")
			->from("user")
			->where('sns_uid = :sns_uid', array(":sns_uid" => $sns_uid))
			->queryRow();
		if (!empty($user)) {
			return $user;
		}
		return FALSE; 	    	
    }
	
	protected function regUser($basic_account = array()) {
		$newUser = array();
		$newUser = array(
			"sns_uid" => $basic_account["idstr"],
			'screen_name' => $basic_account["screen_name"],
			"avatar" => $basic_account["avatar_large"],
			//"access_token" => $access_token,
			'reg_datetime' => time()
		);
		$mUser = new User();
		foreach ($newUser as $property => $value) {
			$mUser->{$property} = $value;
		}
		if ($mUser->insert()) {
			$newUser["uid"] = $mUser->getPrimaryKey();
			return $newUser;
		}
		return FALSE;
	}
	
	/**
	 * 更新sns用户信息
	 */
	protected function refreshSnsUser($uid, $access_token, $basic_account = array()) {
		$data = array();
		$data = array(
			'uid' => (int)$uid,
			'screen_name' => $basic_account['screen_name'],
			'avatar' => $basic_account['avatar_large'],
			"access_token" => $access_token
		);
		
		$mUser = new User();
		$mUser->setIsNewRecord(false);
		foreach ($data as $property => $value) {
			$mUser->{$property} = $value;
		}
		$mUser->update();
	}
}