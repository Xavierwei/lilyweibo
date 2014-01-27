<?php

/**
 * This is the model class for table "friend".
 *
 * The followings are the available columns in table 'friend':
 * @property string $fid
 * @property integer $uid
 * @property string $friend_sns_id
 * @property integer $share_datetime
 */
class Friend extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'friend';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cid, uid, share_datetime', 'numerical', 'integerOnly'=>true),
			array('friend_sns_uid', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('fid, uid, friend_sns_id, share_datetime', 'safe', 'on'=>'search'),
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
			'fid' => 'Fid',
			'uid' => 'Uid',
      'cid' => 'Cid',
			'friend_sns_uid' => 'Friend Sns',
			'share_datetime' => 'Share Datetime',
		);
	}
	
	/**
	 * 判断好友是否分享过
	 */
	public function checkIsShared($cid, $friend_sns_uid) {
		return $this->find(array(
			'condition' => 'cid = :cid AND friend_sns_uid = :friend_sns_uid',
			'params' => array(':cid' => (int)$cid, ':friend_sns_uid' => trim($friend_sns_uid))
		));
	}

  public function getInvitedFriend($uid) {
    return Yii::app()->db->createCommand('SELECT friend_sns_uid FROM friend WHERE uid = '.$uid)->queryAll();
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

		$criteria->compare('fid',$this->fid,true);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('friend_sns_id',$this->friend_sns_id,true);
		$criteria->compare('share_datetime',$this->share_datetime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Friend the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
