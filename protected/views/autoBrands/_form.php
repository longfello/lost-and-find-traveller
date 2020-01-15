<?php
/* @var $this AutoBrandsController */
/* @var $model AutoBrands */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('BsActiveForm', array(
	'id'=>'auto-brands-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'brand'); ?>
		<?php echo $form->textField($model,'brand',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'brand'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->