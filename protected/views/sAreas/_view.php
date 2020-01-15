<?php
/* @var $this SAreasController */
/* @var $data SAreas */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_sa')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_sa), array('view', 'id'=>$data->id_sa)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_settlement')); ?>:</b>
	<?php echo CHtml::encode($data->idSettlement->name); ?>
	<br />


</div>