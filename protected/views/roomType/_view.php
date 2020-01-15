<?php
/* @var $this RoomTypeController */
/* @var $data RoomType */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_rt')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_rt), array('view', 'id'=>$data->id_rt)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('places')); ?>:</b>
	<?php echo CHtml::encode($data->places); ?>
	<br />


</div>