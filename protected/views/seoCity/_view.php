<?php
/* @var $this SeoCityController */
/* @var $data SeoCity */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city_id')); ?>:</b>
	<?php echo CHtml::encode($data->settlements->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('seo_text')); ?>:</b>
	<?php echo CHtml::encode($data->seo_text); ?>
	<br />


</div>