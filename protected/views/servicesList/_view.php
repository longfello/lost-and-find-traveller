<?php
/* @var $this ServicesListController */
/* @var $data ServicesList */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_sl')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_sl), array('view', 'id'=>$data->id_sl)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />


</div>