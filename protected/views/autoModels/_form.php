<?php
/* @var $this AutoModelsController */
/* @var $model AutoModels */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('BsActiveForm', array(
	'id'=>'auto-models-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_type'); ?>
		<?php echo $form->dropDownList($model,'id_type', CHtml::listData(AutoTypes::model()->findAll(), 'id', 'name_ru')); ?>
		<?php echo $form->error($model,'id_brand'); ?>
	</div>

  <div class="row">
    <?php echo $form->labelEx($model,'id_brand'); ?>
    <?php echo $form->dropDownList($model,'id_brand', CHtml::listData($brands, 'id_brand', 'brand')); ?>
    <?php echo $form->error($model,'id_brand'); ?>
  </div>

	<div class="row">
		<?php echo $form->labelEx($model,'model'); ?>
		<?php echo $form->textField($model,'model',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'model'); ?>
	</div>

  <div class="row">
    <?php echo $form->labelEx($model,'class'); ?>
    <?php echo $form->dropDownList($model,'class', EnumAutoClasses::getDataForDropDown()); ?>
    <?php echo $form->error($model,'class'); ?>
  </div>

	<div class="row buttons">
		<?php echo BsHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->