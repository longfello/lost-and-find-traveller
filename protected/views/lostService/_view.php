<?php
/* @var $this LostServiceController */
/* @var $data LostService */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_ls')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_ls), array('view', 'id'=>$data->id_ls)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contact_phone')); ?>:</b>
	<?php echo CHtml::encode($data->contact_phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_thing')); ?>:</b>
	<?php echo CHtml::encode($data->id_thing); ?>
	<br />


</div>