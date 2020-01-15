<?php
/* @var $this PoputchikOrderController */
/* @var $data PoputchikOrder */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_order')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_order), array('view', 'id'=>$data->id_order)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_order')); ?>:</b>
	<?php echo CHtml::encode($data->type_order); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('target')); ?>:</b>
	<?php echo CHtml::encode($data->target); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_route')); ?>:</b>
	<?php echo CHtml::encode($data->type_route); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_route')); ?>:</b>
	<?php echo CHtml::encode($data->id_route); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_path')); ?>:</b>
	<?php echo CHtml::encode($data->id_path); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('transit')); ?>:</b>
	<?php echo CHtml::encode($data->transit); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('direction')); ?>:</b>
	<?php echo CHtml::encode($data->direction); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_settlement')); ?>:</b>
	<?php echo CHtml::encode($data->id_settlement); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_sa')); ?>:</b>
	<?php echo CHtml::encode($data->id_sa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('from_place')); ?>:</b>
	<?php echo CHtml::encode($data->from_place); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('to_place')); ?>:</b>
	<?php echo CHtml::encode($data->to_place); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sum')); ?>:</b>
	<?php echo CHtml::encode($data->sum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_sum')); ?>:</b>
	<?php echo CHtml::encode($data->type_sum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_time')); ?>:</b>
	<?php echo CHtml::encode($data->type_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_to')); ?>:</b>
	<?php echo CHtml::encode($data->date_to); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_from_1')); ?>:</b>
	<?php echo CHtml::encode($data->time_from_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_from_2')); ?>:</b>
	<?php echo CHtml::encode($data->time_from_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_to_1')); ?>:</b>
	<?php echo CHtml::encode($data->time_to_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_to_2')); ?>:</b>
	<?php echo CHtml::encode($data->time_to_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reverse')); ?>:</b>
	<?php echo CHtml::encode($data->reverse); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_reverse')); ?>:</b>
	<?php echo CHtml::encode($data->date_reverse); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_r_from_1')); ?>:</b>
	<?php echo CHtml::encode($data->time_r_from_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_r_from_2')); ?>:</b>
	<?php echo CHtml::encode($data->time_r_from_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_r_to_1')); ?>:</b>
	<?php echo CHtml::encode($data->time_r_to_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_r_to_2')); ?>:</b>
	<?php echo CHtml::encode($data->time_r_to_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
	<?php echo CHtml::encode($data->phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_auto')); ?>:</b>
	<?php echo CHtml::encode($data->type_auto); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_brand')); ?>:</b>
	<?php echo CHtml::encode($data->id_brand); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_model')); ?>:</b>
	<?php echo CHtml::encode($data->id_model); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('free_places_count')); ?>:</b>
	<?php echo CHtml::encode($data->free_places_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
	<?php echo CHtml::encode($data->comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_order')); ?>:</b>
	<?php echo CHtml::encode($data->date_order); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_available')); ?>:</b>
	<?php echo CHtml::encode($data->date_available); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_user')); ?>:</b>
	<?php echo CHtml::encode($data->id_user); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('operator')); ?>:</b>
	<?php echo CHtml::encode($data->operator); ?>
	<br />

	*/ ?>

</div>