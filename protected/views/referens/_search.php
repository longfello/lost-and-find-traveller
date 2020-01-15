<?php
/* @var $this ReferensController */
/* @var $model Referens */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'IdRef'); ?>
		<?php echo $form->textField($model,'IdRef'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'AliasRef'); ?>
		<?php echo $form->textField($model,'AliasRef',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'NameRef'); ?>
		<?php echo $form->textField($model,'NameRef',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'TurnRef'); ?>
		<?php echo $form->textField($model,'TurnRef'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'OwnerRef'); ?>
		<?php echo $form->textField($model,'OwnerRef'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->