<?php
/* @var $this UserController */

$this->breadcrumbs=array(
	'User'=>array('/user'),
	'Login',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	<?php print_r(Yii::app()->session); ?>
	<a href="<?php echo $weiboUrl; ?>">登录</a>
	<a href="<?php echo Yii::app()->createUrl('/user/logout'); ?>">登出</a>
</p>
