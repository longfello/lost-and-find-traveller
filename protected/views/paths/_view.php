<?php
/* @var $this PathsController */
/* @var $data Paths */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_path')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_path), array('view', 'id'=>$data->id_path)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_settlement_1')); ?>:</b>
	<?php echo CHtml::encode($data->id_settlement_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_settlement_2')); ?>:</b>
	<?php echo CHtml::encode($data->id_settlement_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('distance')); ?>:</b>
	<?php echo CHtml::encode($data->distance); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />


</div>