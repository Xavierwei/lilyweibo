<?php
class ScarfController extends Controller {
	public $request = NULL;
	public $user = NULL;
	public $acceptType = NULL;
	public $styleImages = NULL;
	
    public function init() {
        parent::init();
		$this->request = Yii::app()->request;
		$this->user = Yii::app()->user;
		$this->styleImages = array(
			'1' => ROOT_PATH . '/images/weibo_style/1.png',
			'2' => ROOT_PATH . '/images/weibo_style/2.png',
			'3' => ROOT_PATH . '/images/weibo_style/2.png',
		);
		$this->acceptType = strpos($this->request->getAcceptTypes(), 'json') ? 'json' : 'xml';
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
	
	public function actionIndex()
	{
		$this->render('index');
	}

    public function adminIsLogin() {
        return Yii::app()->session["admin_login"];
    }
	
    public static function isLogin() {
        return Yii::app()->session["is_login"] == "true";
    }
	
	/**
	 * 围巾数据列表，GET方法
	 * 
	 * 错误描述：
	 * 1001：请求有误
	 */
	public function actionList() {
		if (1) {
			$page = (int)$this->request->getQuery('page');
			if ($page <= 0) {
				$page = 1;
			}
			$pagenum = (int)$this->request->getQuery('pagenum', 10);
			if ($pagenum <= 0) {
				$page = 10;
			}
			$offset = ($page - 1) * $pagenum;
			
			$list = array();
			if ($this->request->getQuery('status')) {
				$status = (int)$this->request->getQuery('status');
				$list = Yii::app()->db->createCommand()
						->select('*')
						->from('scarf')
						->where('status = :status', array(':status' => $status))
						->limit($pagenum, $offset)
						->order('cid DESC')
						->queryAll();
			} else {
				$list = Yii::app()->db->createCommand()
						->select('*')
						->from('scarf')
						->limit($pagenum, $offset)
						->order('cid DESC')
						->queryAll();
			}
			
			if (!empty($list)) {
				foreach($list as $index => $row) {
					$user = User::model()->getUserInfo($row['uid']);
					$list[$index]['user']['screen_name'] = $user->screen_name;
					$list[$index]['user']['avatar'] = $user->avatar;
					unset($list[$index]['uid']);
				}
			}
			$this->returnJSON($list);
		} else {
			$this->returnJSON($this->error("it is not post", 1001));
		}
	}

	/**
	 * 围巾排名数据列表，GET方法
	 *
	 * 错误描述：
	 * 1001：请求有误
	 */

	public function actionRankList() {
		$page = (int)$this->request->getQuery('page',1);
		$pagenum = (int)$this->request->getQuery('pagenum',10);
		$scarf = new Scarf();
		$rankList = $scarf->getScarfRankList($page, $pagenum);
		return $this->returnJSON($rankList);
	}
	
	/**
	 * 预览微博图片
	 * 
	 * 错误描述：
	 * 1001：未登录
	 * 1002：内容不能为空
	 * 1003：微博风格没有选择
	 * 1004：请求必须为post
	 */
	public function actionGetimage() {
		if ($this->request->isPostRequest && $this->request->isAjaxRequest) {
            if (!self::isLogin()) {
                return $this->returnJSON($this->error("not login", 1001));
            }
			$content = trim($this->request->getPost('content'));
			if (empty($content)) {
				$this->returnJSON($this->error('content can not be empty', 1002));
			}
			$style = $this->request->getPost('style');
			if (!isset($style)) {
				$this->returnJSON($this->error('please select style', 1003));
			}
			if (!in_array($style, array_keys($this->styleImages))) {
				$style = 1;
			}
			$image = Yii::app()->session['image'] = $this->generateTextImg($content, $this->styleImages[$style]);
			$this->returnJSON(array('image' => $image));
		} else {
			$this->returnJSON($this->error('error', 1004));
		}
	}
	
	/**
	 * 生成文字图片
	 */
	private function generateTextImg($text, $bgImg) {
		$generateImgPath = date('Ymd') . '/';
		$imgPath = Yii::app()->params['uploadPath'] . $generateImgPath;
		if (!is_dir($imgPath)) {
			mkdir($imgPath, 077, TRUE);
		}
		$imgName = time() . rand(10000, 99999) .  '.jpg';
		$imgFile = $imgPath . $imgName;
		$imgUrl = Yii::app()->params['uploadUrl'] . $generateImgPath . $imgName;
		
		//合并文字图片
		$im = imagecreatefrompng($bgImg);
		$fontColor  = imagecolorallocate($im, 255, 255, 255); //这是文字颜色，绿色
		$font = ROOT_PATH . '/font/msyh.ttf';
		$fontSize = 23;
    imagettftext($im, $fontSize,0, 88, 59, $fontColor ,$font, $text);
		imagejpeg($im, $imgFile);  
		
		return '/uploads/' . $generateImgPath . $imgName;
	}
	
	/**
	 * 发表微博
	 * 
	 * 错误描述：
	 * 1001：未登录
	 * 1002：内容不能为空
	 * 1003：微博风格没有选择
	 * 1004：请求必须为post
	 */
	public function actionPost() {
		if ($this->request->isPostRequest && $this->request->isAjaxRequest) {
			if (!self::isLogin()) {
					return $this->returnJSON($this->error("not login", 1002));
			} else {
					$uid = Yii::app()->session["user"]["uid"];
			}
			$content = trim($this->request->getPost('content'));
			if (empty($content)) {
				$this->returnJSON($this->error('content can not be empty', 1003));
			}
			$style = $this->request->getPost('style');
			if (!isset($style)) {
				$this->returnJSON($this->error('please select style', 1004));
			}
			if (!in_array($style, array_keys($this->styleImages))) {
				$style = 1;
			}

			$model = new Scarf();
			$newScarf = array(
				'uid' => $uid,
				'content' => $content,
				'style' => $style,
				'image' => Yii::app()->session['image'],
				'add_datetime' => time(),
				'updae_datetime' => time(),
				'rank' => $model->getApprovedCount(), //将当前approved状态的总数作为rank因子
			);
			$model->attributes = $newScarf;
			$model->insert();
			$newScarf['cid'] = $model->getPrimaryKey();
			$newScarf['user'] = array(
				'screen_name' => Yii::app()->session['user']['screen_name'],
				'avatar' => Yii::app()->session['user']['avatar'],
			);
			unset($newScarf['uid']);
			return $this->returnJSON($newScarf);
		} else {
			$this->returnJSON($this->error('bad request', 1001));
		}
	}

	/**
	 * 大冒险
	 */
	public function actionDmx() {
		if(self::isLogin()) {
			$user = Yii::app()->session["user"];
			$scarf = new Scarf();
			$currentRank = (int)$scarf->getUserRank($user['uid']); //获取当前排名
			$randomRankValue = $scarf->getRandomRankValue(); //获取随机的rank因子
			if($scarf->updateRank($user['uid'], $randomRankValue)) //更新当前用户的rankValue
			{
				$newRank = (int)$scarf->getUserRank($user['uid']); //获取新排名
				$data['offset'] = $currentRank - $newRank;
				$data['newRank'] = $newRank;
				return $this->returnJSON(array(
					"data" => $data,
					"error" => null
				));
			}
			else {
				$this->returnJSON($this->error('unknow error', 1002));
			}
		}
		else
		{
			$this->returnJSON($this->error('not login', 1001));
		}
	}

	
	/**
	 * 获得当前用户的排名，GET方法（管理员可以传入get值)
	 */
	public function actionMyRank()
	{
		if(self::isLogin()) {
			$scarf = new Scarf();
			$user = Yii::app()->session["user"];
			$myRank['user']['screen_name'] = $user['screen_name'];
			$myRank['user']['avatar'] = $user['avatar'];
			$myRank['total'] = $scarf->getApprovedCount();
			//判断当前用户是否发过微博
			if($myScarf = $scarf->isCreated($user['uid'])) {
				$myRank['rank'] = (int)$scarf->getUserRank($user['uid']);
				$myRank['image'] = $myScarf->image;
				$myRank['status'] = (int)$myScarf->status;
			}
			return $this->returnJSON(array(
				"data" => $myRank,
				"error" => null
			));
		} else {
			$this->returnJSON($this->error('not login', 1001));
		}
	}

	public function actionPut()
	{
		$this->render('put');
	}

	/**
	 * 分享给好友, POST方法
	 */
	public function actionShare() {
		if ($this->request->isPostRequest) {
			if (!self::isLogin()) {
				return $this->returnJSON($this->error('no login', 1001));
			}
			$cid = $this->request->getPost('cid');
			$friend_sns_uid = trim($this->request->getPort('friend_sns_uid'));
			if (!is_numeric($cid) || empty($cid)) {
				$this->returnJSON($this->error('cid is invalid', 1002));
			}
			if (empty($friend_sns_uid)) {
				$this->returnJSON($this->error('please select friend', 1003));
			}
			
			//判断好友是否分享过
			if (Friend::model()->checkIsShared($cid, $friend_sns_uid)) {
				$this->returnJSON($this->error('have share this scarf', 1004));
			}
			
			//分享微博
			$data = array();
			$data = array(
				'cid' => $cid,
				'friend_sns_uid' => $friend_sns_uid,
				'share_datetime' => time()
			);
			$mFriend = new Friend();
			$mFriend->attributes = $data;
			$ret = $mFriend->insert();
			if ($ret) {
				$this->returnJSON(array('data'));
			}
		} else {
			$this->returnJSON($this->error('Illegal request', 1001));
		}
	}
	
	public static function sendResponse($status = 200, $body = '', $contentType = 'text/html') {
		$statusHeader = 'HTTP/1.1 ' . $status . ' ' . self::getStatusCodeMessage($status);
		header($statusHeader);
		header('Content-Type: '  . $contentType);
		echo $body;
		Yii::app()->end();
	}
	
	public static function getStatusCodeMessage($status) {
		$codes = array(
			200 => 'OK',
			400 => 'Bad Request',
			401 => 'Unauthorized',
		);
		return isset($codes[$status]) ? $codes[$status] : '';
	}
}