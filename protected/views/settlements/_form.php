<?php
/* @var $this SettlementsController */
/* @var $model Settlements */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'settlements-form',
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
			<?php echo CHtml::label('Страна','country_1'); ?>
			<?php echo CHtml::dropDownList('country_1', $id_country_1, CHtml::listData($countries, 'id_country', 'name'),
				array(
					'empty' => Yii::t('poputchik', "Выберите страну"),
					'ajax' => array(
						'type'=>'POST', //request type
						'url'=>CController::createUrl('regions/getRegionsByCountry'), //url to call.
						'update'=>'#Settlements_id_region', //selector to update
						'data' => array(
							'id_country' => 'js:this.value',
						),
						//leave out the data key to pass all form values through
				))			
			) ; ?>
		</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_region'); ?>
		<?php echo $form->dropDownList($model,'id_region', CHtml::listData($regions, 'id_region', 'name'),
		array(
			'empty' => "Выберите регион",
			'ajax' => array(
				'type'=>'POST', //request type
				'url'=>CController::createUrl('areas/getAreasByRegion'), //url to call.
				//Style: CController::createUrl('currentController/methodToCall')
				'update'=>'#Settlements_id_area', //selector to update
				//'data'=>'js:javascript statement' 
				//leave out the data key to pass all form values through
			))
		); ?>
		<?php echo $form->error($model,'id_region'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_area'); ?>
		<?php echo $form->dropDownList($model,'id_area', $areas ? CHtml::listData($areas, 'id_area', 'name') : array()); ?>
		<?php echo $form->error($model,'id_area'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kod_t_st'); ?>
		<?php echo $form->dropDownList($model,'kod_t_st', CHtml::listData($socrnames, 'kod_t_st', 'socrname')); ?>
		<?php echo $form->error($model,'kod_t_st'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ?  'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->