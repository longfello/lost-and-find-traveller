<?php
/* @var $this PoputchikOrderController */
/* @var $model PoputchikOrder */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id_order'); ?>
		<?php echo $form->textField($model,'id_order'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'type_order'); ?>
		<?php echo $form->textField($model,'type_order'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'target'); ?>
		<?php echo $form->textField($model,'target'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'type_route'); ?>
		<?php echo $form->textField($model,'type_route'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_route'); ?>
		<?php echo $form->textField($model,'id_route'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_path'); ?>
		<?php echo $form->textField($model,'id_path'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'transit'); ?>
		<?php echo $form->textField($model,'transit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'direction'); ?>
		<?php echo $form->textField($model,'direction'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_settlement'); ?>
		<?php echo $form->textField($model,'id_settlement'); ?>
	</div>


	<div class="row">
		<?php echo $form->label($model,'from_place'); ?>
		<?php echo $form->textField($model,'from_place',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'to_place'); ?>
		<?php echo $form->textField($model,'to_place',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sum'); ?>
		<?php echo $form->textField($model,'sum'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'type_sum'); ?>
		<?php echo $form->textField($model,'type_sum'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'type_time'); ?>
		<?php echo $form->textField($model,'type_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_to'); ?>
		<?php echo $form->textField($model,'date_to'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_from_1'); ?>
		<?php echo $form->textField($model,'time_from_1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_from_2'); ?>
		<?php echo $form->textField($model,'time_from_2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_to_1'); ?>
		<?php echo $form->textField($model,'time_to_1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_to_2'); ?>
		<?php echo $form->textField($model,'time_to_2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'reverse'); ?>
		<?php echo $form->textField($model,'reverse'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_reverse'); ?>
		<?php echo $form->textField($model,'date_reverse'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_r_from_1'); ?>
		<?php echo $form->textField($model,'time_r_from_1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_r_from_2'); ?>
		<?php echo $form->textField($model,'time_r_from_2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_r_to_1'); ?>
		<?php echo $form->textField($model,'time_r_to_1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_r_to_2'); ?>
		<?php echo $form->textField($model,'time_r_to_2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'type_auto'); ?>
		<?php echo $form->textField($model,'type_auto'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_brand'); ?>
		<?php echo $form->textField($model,'id_brand'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_model'); ?>
		<?php echo $form->textField($model,'id_model'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'free_places_count'); ?>
		<?php echo $form->textField($model,'free_places_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_order'); ?>
		<?php echo $form->textField($model,'date_order'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_available'); ?>
		<?php echo $form->textField($model,'date_available'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_user'); ?>
		<?php echo $form->textField($model,'id_user'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'operator'); ?>
		<?php echo $form->textField($model,'operator'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->