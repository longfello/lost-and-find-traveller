<?php
/* @var $this RegionsController */
/* @var $model Regions */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'regions-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>120)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kod_t_st'); ?>
		<?php echo $form->dropDownList($model,'kod_t_st', CHtml::listData($socrnames, 'kod_t_st', 'socrname')); ?>
		<?php echo $form->error($model,'kod_t_st'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_country'); ?>
		<?php echo $form->dropDownList($model,'id_country', CHtml::listData($countries, 'id_country', 'name')); ?>
		<?php echo $form->error($model,'id_country'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ?  'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->