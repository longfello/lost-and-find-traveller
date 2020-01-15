<?php
/* @var $this LostFoundController */
/* @var $data LostFound */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_lf')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_lf), array('view', 'id'=>$data->id_lf)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_user')); ?>:</b>
	<?php echo CHtml::encode($data->id_user); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category')); ?>:</b>
	<?php echo CHtml::encode($data->category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lost_or_found')); ?>:</b>
	<?php echo CHtml::encode($data->lost_or_found); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city')); ?>:</b>
	<?php echo CHtml::encode($data->city); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('place_disc')); ?>:</b>
	<?php echo CHtml::encode($data->place_disc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('thing')); ?>:</b>
	<?php echo CHtml::encode($data->thing); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('operator')); ?>:</b>
	<?php echo CHtml::encode($data->operator); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_filing')); ?>:</b>
	<?php echo CHtml::encode($data->date_filing); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_lf')); ?>:</b>
	<?php echo CHtml::encode($data->date_lf); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
	<?php echo CHtml::encode($data->comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
	<?php echo CHtml::encode($data->phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active_code')); ?>:</b>
	<?php echo CHtml::encode($data->active_code); ?>
	<br />

	*/ ?>

</div>