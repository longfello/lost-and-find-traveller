<?php
/* @var $this LostServiceController */
/* @var $model LostService */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id_ls'); ?>
		<?php echo $form->textField($model,'id_ls'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'contact_phone'); ?>
		<?php echo $form->textField($model,'contact_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_thing'); ?>
		<?php echo $form->textField($model,'id_thing'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->