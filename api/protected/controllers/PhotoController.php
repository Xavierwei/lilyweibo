<?php

class PhotoController extends Controller {

    public $layout = 'main';
    /**
     *
     * @var CHttpRequest
     */
    public $request = NULL;

    public function init() {
        return parent::init();
    }

    public function beforeAction($action) {
        date_default_timezone_set('PRC');
        $this->request = Yii::app()->getRequest();
        return parent::beforeAction($action);
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
    
    public static function isComplete() {
        $user = self::getLoginUser();
        if ($user["email"] == "" || !isset($user["email"])) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Photo the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Photo::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
    
    public function actionListPhotoes() {
        $allowOrderby = array("vote", "datetime");
        
        $num = $this->request->getParam("num");
        $page = $this->request->getParam("page"); // from 1 - x
        $userid = $this->request->getParam("userid");
        $orderby = $this->request->getParam("orderby");
        if (!in_array($orderby, $allowOrderby)) {
            $orderby = "datetime";
        }
        $order = strtolower($this->request->getParam("order"));
        if (!in_array($order, array("desc", "asc"))) {
            // 最新的照片在上面
            $order = "desc";
        }
        if (!is_numeric($page)) {
            $page = 1;
        }
        if (!is_numeric($num)) {
            $num = 10;
        }
        $offset = ( $page - 1 ) * $num;
        
        if ($orderby == "datetime") {
            $orderby = "photo.datetime";
        }
        else {
            $orderby = "vote";
        }
        
        $sql = 'select '
                . 'user.user_id as user_id, '
                . 'count(vote.vote_id) as vote, '
                . 'photo.path as path, '
                . 'user.nickname as nickname, '
                . 'photo.photo_id as photo_id, '
                . 'photo.datetime as datetime '
                . 'from photo '
                . 'left join user on user.user_id=photo.user_id '
                . 'left join vote on vote.photo_id=photo.photo_id ';
        if(!empty($userid)) {
            $sql =  $sql.'where user.user_id='.$userid.' ';
        }
        $sql = $sql. 'group by photo.photo_id '
                . 'order by '. $orderby. ' '. $order.' '
                . 'limit '. $offset. ', '. $num;
        $rows = Yii::app()->db->createCommand($sql)
                ->queryAll(TRUE, array(":num" => $num, ":offset" => $offset));



        return $this->returnJSON(array(
            "data" => $rows,
            "error" => null
        ));
    }
    
    public function actionVote() {
        if ($this->request->isPostRequest) {
            $photo_id = $this->request->getPost("photo_id");
            if (!self::isLogin()) {
                return $this->returnJSON($this->error("not login", NO_LOGIN_ERROR));
            }
            else {
                $user_id = Yii::app()->session["user"]["user_id"];
            }

            $datetime = date("Y-m-d h:m:s");
            // Step1, 先判断用户是否一天投了10次票
            $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("vote")
                    ->where("date(datetime) = :date AND user_id = :user_id", array(":date" => date("Y-m-d"), ":user_id" => $user_id))
                    ->queryAll();
            if (count($rows) >= 10) {
                return $this->returnJSON($this->error("10 times vote already", ERROR_VOTE_LIMIT));
            }
            // Step1, 先判断用户是否一天投过同一作品
            $rows = Yii::app()->db->createCommand()
                ->select("*")
                ->from("vote")
                ->where("date(datetime) = :date AND photo_id = :photo_id AND user_id = :user_id", array(":date" => date("Y-m-d"), ":photo_id" => $photo_id, ":user_id" => $user_id))
                ->queryAll();
            if (count($rows) >= 1) {
                return $this->returnJSON($this->error("voted this already", ERROR_VOTE_LIMIT2));
            }

            // 如果没有超过限制, 则添加一条记录
            else {
                $newVote = array(
                    "photo_id" => $photo_id,
                    "user_id" => $user_id,
                    "datetime" => $datetime,
                );
                $mVote = new Vote();
                $mVote->setIsNewRecord(true);
                $mVote->unsetAttributes();
                $mVote->attributes = $newVote;
                $mVote->insert();
                $newVote['vote_id'] = $mVote->getPrimaryKey();
                
                return $this->returnJSON(array(
                    "data" => $newVote,
                    "error" => NULL
                ));
            }
        }
        else {
            $mPhoto = Photo::model();
            $photo = $mPhoto->findByPk("7");
            $this->render("votephoto", array("photo" => $photo, "user" => $this->getLoginUser()));
        }
    }
    
    /**
     * 返回最后一个被处理的图片
     */
    public function actionLastPhoto() {
        $tmpImage = Yii::app()->session["tmp_upload_image"];
        $user = self::getLoginUser();
        // 用户已经登录后，我们自动把未保存的图片添加到数据库
        if ($user) {
            if (!$tmpImage) {
                return $this->returnJSON($this->error("no last image", NO_LAST_IMAGE_ERROR));
            }
            // 文件上传后，保存数据库记录
            $newPhoto = array(
                "path" => $tmpImage,
                "user_id" => $user["user_id"],
                "vote" => 0,
                "datetime" => date("Y-m-d H:m:s"),
            );
            $mPhoto = new Photo();
            $mPhoto->unsetAttributes();
            $mPhoto->setIsNewRecord(true);
            $mPhoto->attributes = $newPhoto;
            $mPhoto->insert();

            // 插入新的数据后，我们要以JSON格式返回给客户端
            $newPhoto["photo_id"] = $mPhoto->getPrimaryKey();
            // 然后清除掉session离的tmp_upload_image
            Yii::app()->session["tmp_upload_image"] = "";
            return $this->returnJSON(array(
                "data" => $newPhoto,
                "error" => NULL
            ));
        }
        //如果用户没有登录，则我们返回一个错误消息给用户，提醒他登录
        else {
            return $this->returnJSON($this->error("not login", NO_LOGIN_ERROR));
        }
    }

    public function actionUploadImage() {
        // Post image
        if ($this->request->isPostRequest) {
           // $fileUpload = CUploadedFile::getInstanceByName("image");
//            $fileUpload = base64_encode($this->request->getPost('image_base64'));
//            print_r($fileUpload);
            // 图片处理参数
            // 每一个都是必须
            $params = array();
            $params['image'] = $this->request->getPost('image_base64');
            $params['width'] = $this->request->getPost('width');
            $params['height'] = $this->request->getPost('height');
//            $params['x'] = $this->request->getPost('x') < 0 ? 0 : $this->request->getPost('x');
//            $params['y'] = $this->request->getPost('y') < 0 ? 0 : $this->request->getPost('y');
            $params['x'] = $this->request->getPost('x');
            $params['y'] = $this->request->getPost('y');
            $params['rotate'] = $this->request->getPost('rotate');
            $params['cid'] = $this->request->getPost('cid');
            $params['type'] = $this->request->getPost('type');
            $img = str_replace('data:image/jpeg;base64,', '', $params['image']);
            $img = str_replace('data:image/png;base64,', '', $img);
            //$img = str_replace(' ', '+', $img);
            //echo '1'.$img;
            $data = base64_decode($img);
            $file = ROOT."/uploads/tmp/1tmp" . uniqid() . '.png';
            $success = file_put_contents($file, $data);
            if($success) {
                if (!self::isLogin()) {
                    // 如果没有登录，那先保存文件到临时目录，然后登录后继续处理
                    $filename = rand(0, 100000). time();
                    $to = ROOT."/uploads/tmp/tmp".  $filename.".jpg";
                    $to_big = ROOT."/uploads/tmp/tmp".  $filename."-b.jpg";
                    if (!is_dir(ROOT."/uploads/tmp")) {
                        mkdir(ROOT."/uploads/tmp", 0777);
                    }
                    Yii::app()->session["tmp_upload_image"] = str_replace(ROOT, "", $to);
                    $this->_processImage($file, $params, $to,$to_big);
                    // 2. 美白
//                    $grapher = new Graphic();
//                    $grapher->apply_filter($file, $to);
                    unlink($file);
                    // 3. 美白后， 还需要和王力宏的照片合并


                    // 文件上传后，保存数据库记录
//                    $newPhoto = array(
//                        "path" => str_replace(ROOT, "", $to),
//                        "user_id" => 0,
//                        "vote" => 0,
//                        "datetime" => date("Y-m-d h:m:s"),
//                    );
//                    $mPhoto = new Photo();
//                    $mPhoto->unsetAttributes();
//                    $mPhoto->setIsNewRecord(true);
//                    $mPhoto->attributes = $newPhoto;
//                    $mPhoto->insert();

                    // 插入新的数据后，我们要以JSON格式返回给客户端
                    //$newPhoto["photo_id"] = $mPhoto->getPrimaryKey();

                    //返回给客户端，用户没有登录
                    return $this->returnJSON(array(
                        "data" => array(
                            "path" => str_replace(ROOT, "", $to)
                        ),
                        "error" => array(
                            "message" => "not login",
                            "code" => NO_LOGIN_ERROR
                        ),
                    ));
                }
                else {
                    $user = self::getLoginUser();
                    $path = ROOT."/uploads/".$user['user_id'];
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }

                    $filename = time();
                    $to = $path. "/". $filename . ".jpg";
                    $to_big = $path. "/". $filename."-b.jpg";
                    // 保存之前，先处理图片
                    // 1. 裁剪和旋转，美白
                    $this->_processImage($file, $params,$to,$to_big);
                    // 2. 美白
//                    $grapher = new Graphic();
//                    $grapher->apply_filter($file, $to);

                    unlink($file);
                    // 3. 美白后， 还需要和王力宏的照片合并


                    // 文件上传后，保存数据库记录
                    $newPhoto = array(
                        "path" => str_replace(ROOT, "", $to),
                        "user_id" => $user["user_id"],
                        "vote" => 0,
                        "datetime" => date("Y-m-d H:m:s"),
                    );
                    $mPhoto = new Photo();
                    $mPhoto->unsetAttributes();
                    $mPhoto->setIsNewRecord(true);
                    $mPhoto->attributes = $newPhoto;
                    $mPhoto->insert();

                    // 插入新的数据后，我们要以JSON格式返回给客户端
                    $newPhoto["photo_id"] = $mPhoto->getPrimaryKey();
                    return $this->returnJSON(array(
                        "data" => $newPhoto,
                        "error" => NULL
                    ));
                }
            }
            else {
                //返回给客户端，上传失败
                return $this->returnJSON(array(
                    "data" => null,
                    "error" => array(
                        "message" => "upload fail",
                        "code" => WRONG_FILE_TYPE_ERROR
                    ),
                ));
            }

        } else {
            $tmpImage = Yii::app()->session['tmp_upload_image'];
            $this->render("uploadimage", array("tmpImage" => $tmpImage));
        }
    }
    
    public function _processImage($path, $params, $to, $to_big) {
        $image = new Imagick($path);
        $p = 1;
        if($params['type'] == 'desktop') {
            $p = 1000/421;
        }
        if($params['type'] == 'mobile') {
            $p = 1000/380;
        }
        //$p = 510/380;
        //$p = 510/421;

        $orientation = $image->getImageOrientation();
        switch($orientation) {
            case imagick::ORIENTATION_BOTTOMRIGHT:
                $image->rotateimage("#000", 180); // rotate 180 degrees
                break;

            case imagick::ORIENTATION_RIGHTTOP:
                $image->rotateimage("#000", 90); // rotate 90 degrees CW
                break;

            case imagick::ORIENTATION_LEFTBOTTOM:
                $image->rotateimage("#000", -90); // rotate 90 degrees CCW
                break;
        }

        // Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image!
        $image->setImageOrientation(imagick::ORIENTATION_TOPLEFT);

        // 旋转图片
        $image->rotateimage(new ImagickPixel('none'), $params['rotate']);

        // 缩放图片
        $image->resizeImage($params['width']*$p, $params['height']*$p, Imagick::FILTER_LANCZOS, 1, true);


        // 裁剪图片
        $image->cropImage(1000, 1255, $params['x']*$p, $params['y']*$p);

        $image->resizeImage(1000, 1255, Imagick::FILTER_LANCZOS, 1, true);


        // 美白照片
        $image->modulateImage(120, 120, 100);
        //$image->gaussianBlurImage(30,0.5);
        $image->gammaImage(1.1);
        $image->contrastImage(10);

        $white = new Imagick();
        $white->newImage(1000, 1255, "white");
        $white->compositeimage($image, Imagick::COMPOSITE_DEFAULT, 0, 0);



        // 给图片cover一个背景
        $bk = new Imagick($this->getCoverBackground($params['cid']));
        $white->setimagematte(1);
        $white->compositeimage($bk, imagick::COMPOSITE_DEFAULT, 0, 0);


        // 最后保存图片
        $white->writeimage($to_big);
        $white->resizeImage(520, 652, Imagick::FILTER_LANCZOS, 1, true);
        $white->writeimage($to);

        // 清理资源
        $image->clear();
        $image->destroy();

        $white->clear();
        $white->destroy();
        
        $bk->clear();
        $bk->destroy();
    }

    public function getCoverBackground($name = 1) {
        return ROOT.'/medias/cover/'.$name.'.png';
    }

}
