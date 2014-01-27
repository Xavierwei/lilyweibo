<?php

/**
 * This is the model class for table "scarf".
 *
 * The followings are the available columns in table 'scarf':
 * @property string $cid
 * @property integer $uid
 * @property string $content
 * @property integer $style
 * @property string $image
 * @property integer $status
 * @property integer $add_datetime
 * @property integer $update_datetime
 * @property string $rank
 */
class Scarf extends CActiveRecord
{
	//微博状态（0：unaproved；1：approved；2：producting；3：producted：4：deleted）
	const STATUS_UNAPROVED = 0;
	const STATUS_APPROVED = 1;
	const STATUS_PRINTING = 2;
	const STATUS_PRINTED = 3;
	const STATUS_DELETED = 4;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'scarf';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, style', 'required'),
			array('uid, style, status, add_datetime, update_datetime', 'numerical', 'integerOnly'=>true),
			array('image', 'length', 'max'=>100),
			array('rank', 'length', 'max'=>10),
			array('content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cid, uid, content, style, image, status, add_datetime, update_datetime, rank', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'uid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cid' => 'Cid',
			'uid' => 'Uid',
			'content' => 'Content',
			'style' => 'Style',
			'image' => 'Image',
			'status' => 'Status',
			'add_datetime' => 'Add Datetime',
			'update_datetime' => 'Update Datetime',
			'rank' => 'Rank',
		);
	}
	
	public function getScarfList() {
		return $this->findAll();
	}

	/**
	 * 获取排名列表
	 */
	public function getScarfRankList($page, $pagenum) {
		$start = ($page - 1) * $pagenum;
		$offset = $pagenum;
		$connection=Yii::app()->db;
		$sql = "SELECT uid,content,style,image,
          @curRank := @curRank + 1 AS s_rank
					FROM scarf s, (SELECT @curRank := ".$start.") r
					WHERE status = 1
					ORDER BY rank asc, cid asc LIMIT ".$start.",".$offset.";";
		$command=$connection->createCommand($sql);
		$result = $command->queryAll();
		return $result;
	}
	
	/**
	 * 分享微博
   * @param $cid
	 */
	public function shareScarf($cid) {
		$access_token = Yii::app()->session["weibo_access_token"];
		$c = new SaeTClientV2(WB_AKEY, WB_SKEY, $access_token);
    $scarf = $this->getScarfByCid($cid);
    $content = '我的围巾！'. $scarf->content;
    //TODO: 集成测试时替换图片地址
    return $c->upload($content, 'http://img.hb.aicdn.com/5d9bfeddfce1e8309097cca7c94f2cfd3ae30f1a215df-9uwbCI_fw658');
	}
	
	/**
	 * 根据cid获取围巾信息
	 * @param type $cid
	 */
	public function getScarfByCid($cid) {
		$model = $this->findByPk($cid);
		if ($model) {
			return $model;
		}
		return FALSE;
	}

  /**
   * 根据uid获取围巾信息
   * @param $uid
   */
  public function getScarfByUid($uid) {
    $model = $this->find(array(
      'condition'=>'uid=:uid',
      'params'=>array(':uid'=>$uid),
    ));
    if ($model) {
      return $model;
    }
    return FALSE;
  }

  /**
   * 根据排名数获取Rank因子
   */
  public function getRankValue($rank) {
    $row = Yii::app()->db->createCommand() //获取对应的rank因子
      ->select('rank')
      ->from('scarf')
      ->offset($rank-1)
      ->limit(1)
      ->queryRow();
    return $row['rank'];
  }

  /**
   * 根据随机数获取Rank因子
   */
  public function getRandomRankValue() {
    $max = $this->getApprovedCount();
    $randomRank = rand(1, $max); //获取随机排名
    return $this->getRankValue($randomRank);
  }

  /**
	 * 更新用户排名
	 */
	public function updateRank($uid, $rankValue) {
		$count = Yii::app()->db->createCommand()
			->update('scarf', array(
				'rank' => $rankValue,
			),'status=:status and uid=:uid', array(':status'=>1, ':uid'=>$uid));
		return $count;
	}

  /**
   * 通过Cid更新排名
   */
  public function updateRankByCid($cid, $rankValue) {
    $count = Yii::app()->db->createCommand()
      ->update('scarf', array(
        'rank' => $rankValue,
      ),'status=:status and cid=:cid', array(':status'=>1, ':cid'=>$cid));
    return $count;
  }

	/**
	 * 根据用户名获取排名
	 */
	public function getRankByUid($uid) {
		$connection=Yii::app()->db;
		$command=$connection->createCommand("SELECT COUNT(cid) as rank FROM scarf WHERE status=1 and rank <= (SELECT rank FROM scarf WHERE uid=" . $uid . " limit 0,1)");
		$result = $command->queryRow();
		return $result['rank'];
	}

  /**
   * 根据cid获取排名
   */
  public function getRankByCid($cid) {
    $connection=Yii::app()->db;
    $command=$connection->createCommand("SELECT COUNT(cid) as rank FROM scarf WHERE status=1 and rank <= (SELECT rank FROM scarf WHERE cid=" . $cid . " limit 0,1)");
    $result = $command->queryRow();
    return $result['rank'];
  }

	/**
	 * 验证用户是否已发帖
	 */
	public function isCreated($uid) {
		$criteria=new CDbCriteria;
		$criteria->select='image, status';
		$criteria->addCondition('uid=:uid');
		$criteria->params=array(':uid'=>$uid);
		$criteria->addCondition('status!=4');
		$model = $this->find($criteria);
		return $model;
	}

  /**
   * 验证用户是否是受邀请的
   */
  public function isInvited($uid) {
    $friend = new Friend();
    $criteria=new CDbCriteria;
    $criteria->addCondition('friend_sns_uid=:uid');
    $criteria->params=array(':uid'=>$uid);
    $model = $friend->find($criteria);
    return $model;
  }

	/**
	 * 获取已发布状态的微博总数
	 */
	public function getApprovedCount() {
		$criteria=new CDbCriteria;
		$criteria->addCondition('status=1');
		$model = $this->count($criteria);
		return $model;
	}

  /**
   * 存入大冒险日志
   */
  public function logDMX($uid) {
    $count = Yii::app()->db->createCommand() //获取对应的rank因子
      ->insert('dmx_log', array(
        'uid' => $uid,
        'dmx_datetime' => time()
      ));
    return $count;
  }

  /**
   * 获取今日大冒险的次数
   */
  public function getTodayDmxCount() {
    $start_time = strtotime(date("Y-m-d"));
    $count = Yii::app()->db->createCommand() //获取对应的rank因子
      ->select('count(*) as count')
      ->from('dmx_log')
      ->where('dmx_datetime > :start_time', array(':start_time'=>$start_time))
      ->queryRow();
    return $count['count'];
  }


  /**
   * 立即打印
   */
  public function produceNow() {
    $top1 = $this->getScarfRankList(1,1); //获取排名第一的记录
    Yii::app()->db->createCommand() //将当前打印中的状态改为已打印
      ->update('scarf', array(
        'status' => 3,
        'update_datetime' => time()
      ),'status=2');

    Yii::app()->db->createCommand() //将当前第一名的状态改为打印中
      ->update('scarf', array(
        'status' => 2,
      ),'cid=:cid', array(':cid'=>$top1[0]['cid']));
  }

  /**
   * 更新状态
   */
  public function updateStatus($cid, $status) {
    $count = Yii::app()->db->createCommand() //将当前第一名的状态改为打印中
      ->update('scarf', array(
        'status' => $status,
      ),'cid=:cid', array(':cid'=>$cid));
    return $count;
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

		$criteria->compare('cid',$this->cid,true);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('style',$this->style);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('add_datetime',$this->add_datetime);
		$criteria->compare('update_datetime',$this->update_datetime);
		$criteria->compare('rank',$this->rank,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Scarf the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
