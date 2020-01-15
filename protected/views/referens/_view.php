<?php
/* @var $this ReferensController */
/* @var $data Referens */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('IdRef')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->IdRef), array('view', 'id'=>$data->IdRef)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('AliasRef')); ?>:</b>
	<?php echo CHtml::encode($data->AliasRef); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NameRef')); ?>:</b>
	<?php echo CHtml::encode($data->NameRef); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TurnRef')); ?>:</b>
	<?php echo CHtml::encode($data->TurnRef); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('OwnerRef')); ?>:</b>
	<?php echo CHtml::encode($data->OwnerRef); ?>
	<br />


</div>