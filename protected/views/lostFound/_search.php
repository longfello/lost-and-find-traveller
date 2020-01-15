<?php
/* @var $this LostFoundController */
/* @var $model LostFound */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id_lf'); ?>
		<?php echo $form->textField($model,'id_lf'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_user'); ?>
		<?php echo $form->textField($model,'id_user'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'category'); ?>
		<?php echo $form->textField($model,'category'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lost_or_found'); ?>
		<?php echo $form->textField($model,'lost_or_found'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'city'); ?>
		<?php echo $form->textField($model,'city'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'place_disc'); ?>
		<?php echo $form->textField($model,'place_disc',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'thing'); ?>
		<?php echo $form->textField($model,'thing',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operator'); ?>
		<?php echo $form->textField($model,'operator'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_filing'); ?>
		<?php echo $form->textField($model,'date_filing'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_lf'); ?>
		<?php echo $form->textField($model,'date_lf'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'comment'); ?>
		<?php echo $form->textField($model,'comment',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>15,'maxlength'=>15)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'active_code'); ?>
		<?php echo $form->textField($model,'active_code',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->