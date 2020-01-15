<?php
/* @var $this SettlementsController */
/* @var $data Settlements */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_settlement')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_settlement), array('view', 'id'=>$data->id_settlement)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name.', '.$data->kodTSt->socrname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aoid')); ?>:</b>
	<?php echo CHtml::encode($data->aoid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_region')); ?>:</b>
	<?php echo CHtml::encode($data->idRegion->name.', '.$data->idRegion->kodTSt->socrname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_area')); ?>:</b>
	<?php echo CHtml::encode($data->idArea->name); ?>
	<br />

</div>