<?php
/* @var $this AreasController */
/* @var $data Areas */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_area')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_area), array('view', 'id'=>$data->id_area)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name).', '.CHtml::encode($data->kodTSt->socrname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aoid')); ?>:</b>
	<?php echo CHtml::encode($data->aoid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_region')); ?>:</b>
	<?php echo CHtml::encode($data->idRegion->name).', '.CHtml::encode($data->idRegion->kodTSt->socrname); ?>
	<br />


</div>