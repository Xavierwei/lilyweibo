<?php
class ScarfController extends Controller {
	public $request = NULL;
	public $user = NULL;
	public $acceptType = NULL;
	public $styleImages = NULL;
	
  public function init() {
      parent::init();
  $this->request = Yii::app()->request;
  $this->user = Yii::app()->session["user"];
  $this->styleImages = array(
    '1' => ROOT_PATH . '/images/weibo_style/1.png',
    '2' => ROOT_PATH . '/images/weibo_style/2.png',
    '3' => ROOT_PATH . '/images/weibo_style/3.png',
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

  public static function adminIsLogin() {
      return !Yii::app()->user->getIsGuest();
  }

  public static function isLogin() {
      return Yii::app()->session["is_login"] == "true";
  }
	
	/**
	 * 根据用户昵称查询微博【管理员】
	 * 
	 * 错误描述：
	 * 1001： 没有权限
	 * 1002：请求不是post
	 * 1003：没有数据
	 */
	public function actionSearch() {
		if (!self::adminIsLogin()) {
			$this->returnJSON($this->error('no permission', 1001));
		}
		$keyword = trim($this->request->getQuery('keyword'));
		$page = (int)$this->request->getQuery('page',1);
		$pagenum = (int)$this->request->getQuery('pagenum',10);
		$arrUids = User::model()->getUidsByScreenName($keyword);  
		$scarfList = Scarf::model()->getSearchScarfList($page, $pagenum, $arrUids, $keyword);
		if (!empty($scarfList)) {
			foreach($scarfList as $index => $row) {
				$user = User::model()->getUserInfo($row['uid']);
				$scarfList[$index]['user']['screen_name'] = $user->screen_name;
				$scarfList[$index]['user']['avatar'] = $user->avatar;
				unset($scarfList[$index]['uid']);
			}
			$result['data'] = $scarfList;
			$result['total'] = Scarf::model()->getSearchCount($arrUids, $keyword);
			return $this->returnJSON($result);
		}
		else {
			$this->returnJSON($this->error('end', 1003));
		}
	}
	
	/**
	 * 围巾数据列表，GET方法
	 * 
	 * 错误描述：
	 * 1001：请求有误
	 */
	public function actionList() {
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
		if ($this->request->getQuery('status') != '') {
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
          ->where('status != 4')
					->limit($pagenum, $offset)
					->order('cid DESC')
					->queryAll();
		}

		if (!empty($list)) {
			foreach($list as $index => $row) {
				$user = User::model()->getUserInfo($row['uid']);
				$list[$index]['user']['screen_name'] = $user->screen_name;
				$list[$index]['user']['avatar'] = $user->avatar;
				$list[$index]['user']['sns_uid'] = $user->sns_uid;
				unset($list[$index]['uid']);
			}
		}
		$this->returnJSON($list);
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
		if (!empty($rankList)) {
		  foreach($rankList as $index => $row) {
			$user = User::model()->getUserInfo($row['uid']);
			$rankList[$index]['user']['screen_name'] = $user->screen_name;
			$rankList[$index]['user']['avatar'] = $user->avatar;
			$rankList[$index]['user']['sns_uid'] = $user->sns_uid;
			unset($rankList[$index]['uid']);
		  }
				$result['data'] = $rankList;
				$result['total'] = $scarf->getApprovedCount();
				return $this->returnJSON($result);
		}
		else {
			$this->returnJSON($this->error('end', 1001));
		}
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
			$model = new Scarf();
			if($model->getScarfByUid($this->user['uid'])) {
			  return $this->returnJSON($this->error("already created", 1005));
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
			mkdir($imgPath, 0777, TRUE);
		}
		$imgName = time() . rand(10000, 99999) .  '.jpg';
		$imgFile = $imgPath . $imgName;
		
		//合并文字图片
		$im = imagecreatefrompng($bgImg);
		$fontColor  = imagecolorallocate($im, 255, 255, 255); //这是文字颜色，绿色
		$font = ROOT_PATH . '/font/fzxs.ttf';
		$fontSize = 23;
		$box = imagettfbbox($fontSize, 0, $font, $text);
		$x = (528 - $box[2])/2 + 55;
		imagettftext($im, $fontSize,0, $x, 64, $fontColor ,$font, $text);
		imagejpeg($im, $imgFile);  
		
		return '/uploads/' . $generateImgPath . $imgName;
	}
	
	/**
	 * 发表微博
	 * 
	 * 错误描述：
	 * 1001：未登录
   	 * 1002：内容已创建
	 * 1003：内容不能为空
	 * 1004：微博风格没有选择
	 * 1005：请求必须为post
	 */
	public function actionPost() {
		if ($this->request->isPostRequest && $this->request->isAjaxRequest) {
			if (!self::isLogin()) {
				return $this->returnJSON($this->error("not login", 1002));
			}
			$model = new Scarf();
			if($model->getScarfByUid($this->user['uid'])) {
			  return $this->returnJSON($this->error("already created", 1003));
			}
			$content = trim($this->request->getPost('content'));
			if (empty($content)) {
				$this->returnJSON($this->error('content can not be empty', 1004));
			}
			$style = $this->request->getPost('style');
			if (!isset($style)) {
				$this->returnJSON($this->error('please select style', 1005));
			}
			if (!in_array($style, array_keys($this->styleImages))) {
				$style = 1;
			}

			$newScarf = array(
				'uid' => $this->user['uid'],
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
				'screen_name' => $this->user['screen_name'],
				'avatar' => $this->user['avatar'],
			);
			
			// 分享微博
			$shareText = '我的围巾！'. $newScarf['content'];
			$shareImg = Yii::app()->getBaseUrl(true).$newScarf['image'];
			$model->shareWeibo($shareText, $shareImg);

			$sns_uid = $this->user["sns_uid"];
			if($invitedInfo = $model->isInvited($sns_uid)) //判断当前用户是否是受邀请的
			{
				// 更新邀请人的内容排名
				$cid = $invitedInfo->cid;
				$rank = $model->getRankByCid($cid);
				$rank = $rank - 5; //提升10个排名
				$rankValue = $model->getRankValue($rank);
				$model->updateRankByCid($cid, $rankValue);
			}
			unset($newScarf['uid']);
			return $this->returnJSON(array('data'=>$newScarf, 'error'=>null));
		} else {
			$this->returnJSON($this->error('bad request', 1001));
		}
	}

	public function actionisInvited() {
		$model = new Scarf();
		$sns_uid = Yii::app()->session["user"]["sns_uid"];
		if($invitedInfo = $model->isInvited($sns_uid)) //判断当前用户是否是受邀请的
		{
			// 更新邀请人的内容排名
			$cid = $invitedInfo->cid;
			$rank = $model->getRankByCid($cid);
			$rank = $rank - 10; //提升10个排名
			$rankValue = $model->getRankValue($rank);
			$model->updateRankByCid($cid, $rankValue);
		}
	}


	/**
	 * 大冒险
	 */
	public function actionDmx() {
		if(self::isLogin()) {
			$user = Yii::app()->session["user"];
			$scarf = new Scarf();
			$todayDmxCount = $scarf->getTodayDmxCount($user['uid']); //获得今天大冒险的次数
			if($todayDmxCount >= 3) {
				return $this->returnJSON($this->error('over max', 1003));
			}
			$currentRank = (int)$scarf->getRankByUid($user['uid']); //获取当前排名
			$randomRankValue = $scarf->getRandomRankValue(); //获取随机的rank因子
			if($scarf->updateRank($user['uid'], $randomRankValue)) //更新当前用户的rankValue
			{
				$newRank = (int)$scarf->getRankByUid($user['uid']); //获取新排名
				$scarf->logDMX($user['uid']); //记录大冒险日志
				$data['offset'] = $currentRank - $newRank;
				$data['newRank'] = $newRank;

        // 分享微博
        if($data['offset'] >= 0) {
          $shareText = '我刚赌了一把！排名上升了'.$data['offset'].'名，现在排名'.$data['newRank'].'名';
        }
        else {
          $shareText = '我刚赌了一把！排名下降了'.abs($data['offset']).'名，现在排名'.$data['newRank'].'名';
        }

        //TODO: 更换POSTER
        Scarf::model()->shareWeibo($shareText, 'http://www.lily-collection.com/media/wysiwyg/spring-vedio-poster.jpg');

				return $this->returnJSON(array(
					"data" => $data,
					"error" => null
				));
			}
			else {
				return $this->returnJSON($this->error('unknow error', 1002));
			}
		} else {
			return $this->returnJSON($this->error('not login', 1001));
		}
	}

	
	/**
	 * 获得当前用户的排名，GET方法（管理员可以传入get值)
	 */
	public function actionMyRank() {
		if(self::isLogin()) {
			$scarf = new Scarf();
			$user = Yii::app()->session["user"];
			$myRank['user']['screen_name'] = $user['screen_name'];
			$myRank['user']['avatar'] = $user['avatar'];
			$myRank['total'] = (int)$scarf->getApprovedCount();
			//判断当前用户是否发过微博
			if($myScarf = $scarf->isCreated($user['uid'])) {
				$myRank['rank'] = (int)$scarf->getRankByUid($user['uid']);
				$myRank['image'] = $myScarf->image;
        $myRank['dmxcount'] = 3 - (int)$scarf->getTodayDmxCount($user['uid']); //获得今天大冒险的次数
				$myRank['status'] = (int)$myScarf->status;
				return $this->returnJSON(array("data" => $myRank));
			} else {
				return $this->returnJSON(array('data' => $myRank,'error'=>1002));
			}
			return $this->returnJSON(array(
				"data" => $myRank,
				"error" => null
			));
		} else {
			$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
			$weiboUrl = $o->getAuthorizeURL(WB_CALLBACK_URL);
			$this->returnJSON(array('url'=>$weiboUrl,'error'=>1001));
		}
	}

	/**
	 * 邀请好友, POST方法
	 */
	public function actionInvite() {
		if ($this->request->isPostRequest) {
			if (!self::isLogin()) {
				return $this->returnJSON($this->error('no login', 1001));
			}

			$friends = explode(',', trim($this->request->getPost('friends')));
			$shareText = $this->request->getPost('sharetext');
			if (!count($friends)) {
				$this->returnJSON($this->error('please select friend', 1003));
			}
			if (empty($shareText)) {
				$this->returnJSON($this->error('please enter share text', 1004));
			}
			$scarf = new Scarf();
			$user = Yii::app()->session["user"];
			$myScarf = $scarf->isCreated($user['uid']);
			$cid = $myScarf->cid;

			foreach($friends as $friend) {
			 // echo $friend;
			  if (!Friend::model()->checkIsShared($cid, $friend)) {
				$data = array(
				  'cid' => $cid,
				  'uid' => $user['uid'],
				  'friend_sns_uid' => $friend,
				  'share_datetime' => time()
				);
				$mFriend = new Friend();
				$mFriend->attributes = $data;
				$mFriend->insert();
			  }
			}
      $shareImg = Yii::app()->getBaseUrl(true).$myScarf['image'];
			Scarf::model()->shareWeibo($shareText, $shareImg);
			return $this->returnJSON(array(
				"error" => null
			));
		} else {
			return $this->returnJSON($this->error('Illegal request', 1001));
		}
	}

	/**
	 * 获取已邀请的好友列表
	 */
	public function actionGetInvitedFriends() {
	  $friends = Friend::model()->getInvitedFriend(Yii::app()->session["user"]["uid"]);
	  $this->returnJSON(array('data'=>$friends, 'error'=>null));
	}

	public function actionUpdateStatus()
	{
		if ($this->request->isPostRequest) {
			if (!self::adminIsLogin()) {
				$this->returnJSON($this->error('no permission', 1002));
			}
			$cid = $this->request->getPost('cid');
			$status = $this->request->getPost('status');
			$scarf = new Scarf();
			$scarf->updateStatus($cid, $status);
		}
		else {
		  $this->returnJSON($this->error('Illegal request', 1001));
		}
	}

	public function actionProduceNow()
	{
		if (!self::adminIsLogin()) {
			$this->returnJSON($this->error('no permission', 1001));
		}
		$scarf = new Scarf();
		$scarf->produceNow();
	}

  public function actionGetStatistics()
  {
    if (!self::adminIsLogin()) {
      $this->returnJSON($this->error('no permission', 1001));
    }
    $scarf = new Scarf();
    $statistics = $scarf->getStatistics();
    return $this->returnJSON(array('data'=>$statistics, 'error'=>null));
  }
	
	/**
	 * 发送私信【管理员】
	 * @TODO 接口选哪个？
	 */
	public function sendMessage() {
		if (!self::adminIsLogin()) {
			$this->returnJSON($this->error('no permission', 1001));
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


  public function actionTest(){

    for($i = 0; $i < 3000; $i++)
    {
      $user = new User();
      $user->sns_uid = 0;
      $user->screen_name = '机器人'.$i;
      $user->avatar = 'http://tp2.sinaimg.cn/2144684673/50/5679228493/1';
      $user->save(false);

      $uid = $user->primaryKey;

      $scarf = new Scarf();
      $scarf->uid = $uid;
      $scarf->content = '我是第'.$i.'条漂亮的围巾';
      $scarf->style = 1;
      $scarf->image = '/uploads/20140128/139091279544827.jpg';
      $scarf->status = 1;
      $scarf->rank = $i;
      $scarf->add_datetime = time();
      $scarf->save(false);
    }

  }
}