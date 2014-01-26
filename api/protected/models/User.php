<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $uid
 * @property string $sns_id
 * @property string $screen_name
 * @property string $avatar
 * @property string $access_token
 * @property integer $reg_datetime
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reg_datetime', 'numerical', 'integerOnly'=>true),
			array('sns_id, access_token', 'length', 'max'=>15),
			array('screen_name', 'length', 'max'=>30),
			array('avatar', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('uid, sns_id, screen_name, avatar, access_token, reg_datetime', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'uid' => 'Uid',
			'sns_id' => 'Sns',
			'screen_name' => 'Screen Name',
			'avatar' => 'Avatar',
			'access_token' => 'Access Token',
			'reg_datetime' => 'Reg Datetime',
		);
	}
	
	/**
	 * 取得用户信息
	 * @param   int     $uid  用户id
	 * @return  mixed   如果找到，返回对应的CActiveRecord模型，如果没有则返回NULL
	 */
	public function getUserInfo($uid)
	{
		return User::model()->findByPk($uid);
	}

	/**
	 * 取得好友列表
	 * @param   int     $sns_uid  用户微博id
	 */
	public function getUserFriend($sns_uid)
	{
		$access_token = Yii::app()->session["weibo_access_token"];
		$c = new SaeTClientV2(WB_AKEY, WB_SKEY, $access_token);
		$friendList = $c->bilateral($sns_uid);

		return $friendList;
	}

	/**
	 * 邀请好友
	 * @param   int     $sns_uid  用户微博id
	 */
	public function invitFriend($friend_sns_uid)
	{
		$access_token = Yii::app()->session["weibo_access_token"];
		$c = new SaeTClientV2(WB_AKEY, WB_SKEY, $access_token);
		$data = '{"text": "这个游戏太好玩了，加入一起玩吧","url": "http://app.sina.com.cn/appdetail.php?appID=770915","invite_logo": "http://hubimage.com2us.com/hubweb/contents/123_499.jpg"}';
		$result = $c->invite($friend_sns_uid, urlencode($data));
		print_r($result);
		return $result;
	}
	

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('sns_id',$this->sns_id,true);
		$criteria->compare('screen_name',$this->screen_name,true);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('access_token',$this->access_token,true);
		$criteria->compare('reg_datetime',$this->reg_datetime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
