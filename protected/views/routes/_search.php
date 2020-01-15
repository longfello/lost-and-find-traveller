<?php
/* @var $this RoutesController */
/* @var $model Routes */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'start_settlement_search'); ?>
		<?php echo $form->textField($model,'start_settlement_search'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'end_settlement_search'); ?>
		<?php echo $form->textField($model,'end_settlement_search'); ?>
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