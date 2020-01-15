<?php
/* @var $this HotelServiceController */
/* @var $model HotelService */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id_hs'); ?>
		<?php echo $form->textField($model,'id_hs'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>300)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'place_desc'); ?>
		<?php echo $form->textField($model,'place_desc',array('size'=>60,'maxlength'=>500)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>500)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phones'); ?>
		<?php echo $form->textField($model,'phones',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'site_link'); ?>
		<?php echo $form->textField($model,'site_link',array('size'=>60,'maxlength'=>300)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'coord_x'); ?>
		<?php echo $form->textField($model,'coord_x'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'coord_y'); ?>
		<?php echo $form->textField($model,'coord_y'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'type'); ?>
		<?php echo $form->textField($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'guest_rating'); ?>
		<?php echo $form->textField($model,'guest_rating'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stars_rating'); ?>
		<?php echo $form->textField($model,'stars_rating'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'square'); ?>
		<?php echo $form->textField($model,'square'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'price_from'); ?>
		<?php echo $form->textField($model,'price_from'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'price_to'); ?>
		<?php echo $form->textField($model,'price_to'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->