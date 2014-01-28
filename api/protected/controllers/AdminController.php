<?php

class AdminController extends Controller
{
	public $defaultAction = 'index';
	public $request;

	public function init() {
		parent::init();
		$this->request = Yii::app()->getRequest();
	}

  public function actionIndex(){
    $this->layout = 'admin';
    if(Yii::app()->user->getIsGuest()) {
      $this->render('login');
    }
    else {
      $this->render('index');
    }
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

  public function actionLogin() {
    $username = $this->request->getPost("username");
    $password = $this->request->getPost("password");
    $userIdentify = new UserIdentity($username, $password);

    // 验证没有通过
    if (!$userIdentify->authenticate()) {
      $this->redirect(Yii::app()->request->baseUrl.'/admin/index');
    }
    else {
      Yii::app()->user->login($userIdentify);
      $this->redirect(Yii::app()->request->baseUrl.'/admin/index');
    }
  }

  public function actionIsAdmin() {
    if(Yii::app()->user->getIsGuest())
    {
      return $this->returnJSON(array(
        "data" => 0,
        "error" => NULL
      ));
    }
    else {
      return $this->returnJSON(array(
        "data" => 1,
        "error" => NULL
      ));
    }
  }


//  public function actionHashPassword() {
//    $pw = Admin::model()->hashPassword("");
//    print_r($pw);
//  }

	/**
	 * 退出
	 */
	public function actionLogout() {
		Yii::app()->user->logout();
		return $this->returnJSON(array(
			"data" => "logout success",
			"error" => NULL
		));
	}
}