<?php
/* @var $this VoteController */
/* @var $model Vote */

$this->breadcrumbs=array(
	'Votes'=>array('index'),
	$model->user_id,
);

$this->menu=array(
	array('label'=>'List Vote', 'url'=>array('index')),
	array('label'=>'Create Vote', 'url'=>array('create')),
	array('label'=>'Update Vote', 'url'=>array('update', 'id'=>$model->user_id)),
	array('label'=>'Delete Vote', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->user_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Vote', 'url'=>array('admin')),
);
?>

<h1>View Vote #<?php echo $model->user_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'user_id',
		'photo_id',
		'datetime',
	),
)); ?>
