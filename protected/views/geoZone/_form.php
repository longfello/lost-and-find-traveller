<?php
/* @var $this GeoZoneController */
/* @var $model GeoZone */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
	'id'=>'geo-zone-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name_ru'); ?>
		<?php echo $form->textField($model,'name_ru'); ?>
		<?php echo $form->error($model,'name_ru'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name_en'); ?>
		<?php echo $form->textField($model,'name_en'); ?>
		<?php echo $form->error($model,'name_en'); ?>
	</div>

	<div class="row buttons">
		<?php echo BsHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->