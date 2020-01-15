<?php
/* @var $this RoutesController */
/* @var $data Routes */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_settlement')); ?>:</b>
	<?php echo CHtml::encode($data->startSettlement->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_settlement')); ?>:</b>
	<?php echo CHtml::encode($data->endSettlement->name); ?>
	<br />


</div>