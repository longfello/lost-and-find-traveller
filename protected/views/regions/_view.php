<?php
/* @var $this RegionsController */
/* @var $data Regions */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_region')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_region), array('view', 'id'=>$data->id_region)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name).', '.CHtml::encode($data->kodTSt->socrname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_country')); ?>:</b>
	<?php echo CHtml::encode($data->idCountry->name); ?>
	<br />


</div>