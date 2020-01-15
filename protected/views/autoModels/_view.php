<?php
/* @var $this AutoModelsController */
/* @var $data AutoModels */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_model')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_model), array('view', 'id'=>$data->id_model)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_brand')); ?>:</b>
	<?php echo CHtml::encode($data->id_brand); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('model')); ?>:</b>
	<?php echo CHtml::encode($data->model); ?>
	<br />


</div>