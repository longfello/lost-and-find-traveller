<?php
/* @var $this PathsController */
/* @var $model Paths */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'settlement_search'); ?>
		<?php echo $form->textField($model, 'settlement_search'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'empty_search'); ?>
		<?php echo $form->checkBox($model, 'empty_search'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'not_moderate_search'); ?>
		<?php echo $form->checkBox($model, 'not_moderate_search',  array('checked'=>'checked')); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Поиск'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->