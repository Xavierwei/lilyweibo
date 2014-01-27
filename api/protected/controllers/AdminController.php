<?php

class AdminController extends Controller
{
	public $defaultAction = 'login';
	public $request;
	
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

    public function actionLogin() {
		if (!$this->request->isPostRequest) {
			$model = new AdminLoginForm();
			$_POST['AdminLoginForm'] = array(
				'username' => 'admin',
				'password' => 'admin',
			);
			if (isset($_POST["AdminLoginForm"])) {
				$model->attributes = $_POST["AdminLoginForm"];
				if ($model->validate() && $model->login()) {
					return $this->returnJSON(array(
						"data" => "logout success",
						"error" => NULL
					));
				} else {
					$this->returnJSON($this->error('login failed', 1002));
				}
			}
		} else {
			$this->returnJSON($this->error('bad request', 1001));
		}
    }

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