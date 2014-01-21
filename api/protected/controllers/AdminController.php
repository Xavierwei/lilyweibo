<?php

class AdminController extends Controller {

    public $layout = 'admin';
    /**
     *
     * @var CHttpRequest
     */
    public $request = NULL;

    public function returnJSON($data) {
        header("Content-Type: application/json");
        echo CJSON::encode($data);
        Yii::app()->end();
    }

    public function init() {
        return parent::init();
    }

    public function beforeAction($action) {
        $this->request = Yii::app()->getRequest();
        return parent::beforeAction($action);
    }

    public function doWhenLoginSuccess() {
        $drupal_path = dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/data';
        Yii::app()->session["admin_login"] = TRUE;
        if (is_dir($drupal_path)) {
            define('DRUPAL_ROOT', $drupal_path);
            require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
            drupal_bootstrap(DRUPAL_BOOTSTRAP_VARIABLES);
            $ret = drupal_http_request(DRUPAL_URL_CRUL.'yii_login/hejdhsld_sdhjhelo_sd8e_sd');
            $cookie = $ret->headers["set-cookie"];
            foreach (explode(";", $cookie) as $value) {
                list($k, $v) = explode("=",$value);
                if (strpos($k, "SESS") !== FALSE) {
                    $cookie_key = $k;
                    $cookie_value = $v;
                }
            }
            echo $cookie_value;
            setcookie($cookie_key, $cookie_value, 0, "/", "");
        }
    }

    public function adminIsLogin() {
        return !!Yii::app()->session["admin_login"];
    }

    public function actionIndex() {
        if (!$this->adminIsLogin()) {
            return $this->redirect(array("login"));
        }
        // Find out all photo with user and vote count
        $list = Yii::app()->db->createCommand("select p.*, u.nickname, count(v.user_id) as vote_count from photo p left join user u on  p.user_id=u.user_id left join vote v on v.photo_id=p.photo_id group by p.photo_id;")
            ->queryAll();

        $this->render("index", array("list" => $list));
    }

    public function actionLogin() {
        if ($this->adminIsLogin()) {
            return $this->redirect(array("index"));
        }
        $model = new AdminLoginForm();
        if (isset($_POST["AdminLoginForm"])) {
            $model->attributes = $_POST["AdminLoginForm"];
            if ($model->validate()) {
                $this->doWhenLoginSuccess();
                return $this->redirect(array("index"));
            }
        }
        $this->render("login", array('model' => $model));
    }

    public function actionLogout() {
        Yii::app()->session->clear();
        Yii::app()->session->destroy();
        return $this->redirect(DRUPAL_URL.'user/logout');
    }

    public function actionDelete() {
        if (!$this->adminIsLogin()) {
            return;
        }
        $photo_id = Yii::app()->request->getQuery("photo_id");
        if ($photo_id && is_numeric($photo_id)) {
            $ret = Yii::app()->db->createCommand()
                ->delete("photo", "photo_id = :photo_id", array(":photo_id" => $photo_id));
        }

        $user_id = Yii::app()->request->getQuery("user_id");
        if ($user_id && is_numeric($user_id)) {
            $ret = Yii::app()->db->createCommand()
                ->delete("user", "user_id = :user_id", array(":user_id" => $user_id));
        }
    }

    public function actionUser() {
        if (!$this->adminIsLogin()) {
            return $this->redirect(array("index"));
        }
        $list = Yii::app()->db->createCommand()
            ->select("*")
            ->from("user")
            ->queryAll();

        $this->render("user", array("list" => $list));
    }

    public function actionUserDetail() {
        if (!$this->adminIsLogin()) {
            return $this->redirect(array("index"));
        }
        $uid = $this->request->getParam("uid");
        $list = Yii::app()->db->createCommand()
            ->select("nickname,from,email,tel,datetime")
            ->from("user")
            ->where("user_id = :user_id", array(":user_id" => $uid))
            ->queryAll();

        return $this->returnJSON(array(
            "data" => $list,
            "error" => null
        ));
    }

    public function actionExport() {
        if (!$this->adminIsLogin()) {
            return $this->redirect(array("index"));
        }
        require_once "PHPExcel.php";
        $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
        $cacheSettings = array( 'dir'  => ROOT.'/tmp');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $excel = new PHPExcel();
        $excel->getActiveSheet()->setTitle('User');
        // Find out all photo with user and vote count
        $list = Yii::app()->db->createCommand()
            ->select("user_id,nickname,from,email,tel,datetime")
            ->from("user")
            ->queryAll();
        // col title
        $first_col = $list[0];
        $cols = array_keys($first_col);
        $column = 'A';
        foreach ($cols as $cell) {
            $excel->getActiveSheet()->setCellValue($column."1", $cell);
            $column++;
        }
        $rowNumber = 2;
        foreach ($list as $item) {
            $col = 'A';
            foreach ($item as $cell) {
                $excel->getActiveSheet()->setCellValue($col.$rowNumber, $cell);
                $col++;
            }
            $rowNumber++;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="user.xlsx"');
        header('Cache-Control: max-age=0');
        date_default_timezone_set("PRC");
        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

}
