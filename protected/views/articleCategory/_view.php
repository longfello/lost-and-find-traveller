<?php
/* @var $this ArticleCategoryController */
/* @var $data ArticleCategory */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_cat')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_cat), array('view', 'id'=>$data->id_cat)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />


</div>