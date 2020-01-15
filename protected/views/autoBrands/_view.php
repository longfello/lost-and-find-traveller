<?php
/* @var $this AutoBrandsController */
/* @var $data AutoBrands */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_brand')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_brand), array('view', 'id'=>$data->id_brand)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('brand')); ?>:</b>
	<?php echo CHtml::encode($data->brand); ?>
	<br />


</div>