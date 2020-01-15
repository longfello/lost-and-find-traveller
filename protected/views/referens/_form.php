<?php
/* @var $this ReferensController */
/* @var $model Referens */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'referens-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'AliasRef'); ?>
		<?php echo $form->textField($model,'AliasRef',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'AliasRef'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NameRef'); ?>
		<?php echo $form->textField($model,'NameRef',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'NameRef'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TurnRef'); ?>
		<?php echo $form->textField($model,'TurnRef'); ?>
		<?php echo $form->error($model,'TurnRef'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'OwnerRef'); ?>
		<?php echo $form->textField($model,'OwnerRef'); ?>
		<?php echo $form->error($model,'OwnerRef'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->