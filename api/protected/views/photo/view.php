<?php
/* @var $this PhotoController */
/* @var $model Photo */

$this->breadcrumbs=array(
	'Photos'=>array('index'),
	$model->photo_id,
);

$this->menu=array(
	array('label'=>'List Photo', 'url'=>array('index')),
	array('label'=>'Create Photo', 'url'=>array('create')),
	array('label'=>'Update Photo', 'url'=>array('update', 'id'=>$model->photo_id)),
	array('label'=>'Delete Photo', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->photo_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Photo', 'url'=>array('admin')),
);
?>

<h1>View Photo #<?php echo $model->photo_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'photo_id',
		'path',
		'user_id',
		'vote',
		'datetime',
	),
)); ?>
