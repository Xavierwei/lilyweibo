<?php
/* @var $this PhotoController */
/* @var $data Photo */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('photo_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->photo_id), array('view', 'id'=>$data->photo_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('path')); ?>:</b>
	<?php echo CHtml::encode($data->path); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vote')); ?>:</b>
	<?php echo CHtml::encode($data->vote); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetime')); ?>:</b>
	<?php echo CHtml::encode($data->datetime); ?>
	<br />


</div>