<?php
/* @var $this HotelServiceController */
/* @var $data HotelService */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_hs')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_hs), array('view', 'id'=>$data->id_hs)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('place_desc')); ?>:</b>
	<?php echo CHtml::encode($data->place_desc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phones')); ?>:</b>
	<?php echo CHtml::encode($data->phones); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('site_link')); ?>:</b>
	<?php echo CHtml::encode($data->site_link); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('coord_x')); ?>:</b>
	<?php echo CHtml::encode($data->coord_x); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('coord_y')); ?>:</b>
	<?php echo CHtml::encode($data->coord_y); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('guest_rating')); ?>:</b>
	<?php echo CHtml::encode($data->guest_rating); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stars_rating')); ?>:</b>
	<?php echo CHtml::encode($data->stars_rating); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('square')); ?>:</b>
	<?php echo CHtml::encode($data->square); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price_from')); ?>:</b>
	<?php echo CHtml::encode($data->price_from); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price_to')); ?>:</b>
	<?php echo CHtml::encode($data->price_to); ?>
	<br />

	*/ ?>

</div>