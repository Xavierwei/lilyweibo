<?php
require_once('sns/weibo/saetv2.ex.class.php'); 

class ScarfController extends Controller {
	public $request = NULL;
	public $user = NULL;
	public $acceptType = NULL;
	
    public function init() {
        parent::init();
		$this->request = Yii::app()->request;
		$this->user = Yii::app()->user;
		$this->acceptType = strpos($this->request->getAcceptTypes(), 'json') ? 'json' : 'xml';
    }
	
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'AccessControl + index',
		);
	}
	
	public function returnJSON($data) {
		header('Content-Type: application/json');
		echo CJSON::encode($data);
		Yii::app()->end();
	}
	
	public function actionIndex()
	{
		var_dump($this->acceptType);
		exit;
		$this->render('index');
	}

    public function adminIsLogin() {
        return !!Yii::app()->session["admin_login"];
    }
	
	/**
	 * 围巾数据列表，GET方法
	 */
	public function actionList() {
		if (!$this->request->isAjaxRequest) {
			$page = (int)$this->request->getPost('page');
			if ($page <= 0) {
				$page = 1;
			}
			$pagenum = (int)$this->request->getPost('page', 10);
			if ($pagenum <= 0) {
				$page = 10;
			}
			$offset = ($page - 1) * $pagenum;
			
			if (!$this->adminIsLogin()) {
				$status = (int)$this->request->getPost('status');
				$list = Yii::app()->db->createCommand()
						->select('*')
						->from('scarf AS s')
						->where('status = :status', array(':status' => $status))
						//->leftJoin('user AS u', 's.uid = u.uid')
						->limit($pagenum, $offset)
						->order('cid DESC')
						->queryAll();
			} else {
				$list = Yii::app()->db->createCommand()
						->select('cid, content, style, image, rank')
						->from('scarf')
						->limit($pagenum, $offset)
						->order('cid DESC')
						->queryAll();
			}
			
			return $this->returnJSON(array(
				"data" => $list,
			));
		} else {
			
		}
		$this->render('list');
	}
	
	public function actionDmx()
	{
		$this->render('dmx');
	}

	public function actionGetNeighbours()
	{
		$this->render('getNeighbours');
	}

	public function actionMyRank()
	{
		$this->render('myRank');
	}

	public function actionPost()
	{
		$request = Yii::app()->request;
		if ($request->isPostRequest && $request->isAjaxRequest) {
			
		}
		$this->render('post');
	}

	public function actionPut()
	{
		$this->render('put');
	}

	public function actionShare()
	{
		$this->render('share');
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Vote the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Vote::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
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
		return isset($codes[$status]) ? $codes['status'] : '';
	}
}