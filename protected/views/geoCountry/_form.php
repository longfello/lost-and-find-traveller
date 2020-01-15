<?php
/* @var $this GeoCountryController */
/* @var $model GeoCountry */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
	'id'=>'geo-country-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'iso_alpha2'); ?>
		<?php echo $form->textField($model,'iso_alpha2',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'iso_alpha2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'iso_alpha3'); ?>
		<?php echo $form->textField($model,'iso_alpha3',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'iso_alpha3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'iso_numeric'); ?>
		<?php echo $form->textField($model,'iso_numeric'); ?>
		<?php echo $form->error($model,'iso_numeric'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fips_code'); ?>
		<?php echo $form->textField($model,'fips_code',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'fips_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name_ru'); ?>
		<?php echo $form->textField($model,'name_ru',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'name_ru'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name_en'); ?>
		<?php echo $form->textField($model,'name_en',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'name_en'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'capital'); ?>
		<?php echo $form->textField($model,'capital',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'capital'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'areainsqkm'); ?>
		<?php echo $form->textField($model,'areainsqkm'); ?>
		<?php echo $form->error($model,'areainsqkm'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'population'); ?>
		<?php echo $form->textField($model,'population'); ?>
		<?php echo $form->error($model,'population'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'continent'); ?>
		<?php echo $form->textField($model,'continent',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'continent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tld'); ?>
		<?php echo $form->textField($model,'tld',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'tld'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'currency'); ?>
		<?php echo $form->textField($model,'currency',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'currency'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'currencyName'); ?>
		<?php echo $form->textField($model,'currencyName',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'currencyName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Phone'); ?>
		<?php echo $form->textField($model,'Phone',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'Phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postalCodeFormat'); ?>
		<?php echo $form->textField($model,'postalCodeFormat',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'postalCodeFormat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postalCodeRegex'); ?>
		<?php echo $form->textField($model,'postalCodeRegex',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'postalCodeRegex'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'geonameId'); ?>
		<?php echo $form->textField($model,'geonameId'); ?>
		<?php echo $form->error($model,'geonameId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'languages'); ?>
		<?php echo $form->textField($model,'languages',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'languages'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'neighbours'); ?>
		<?php echo $form->textField($model,'neighbours',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'neighbours'); ?>
	</div>

	<div class="row buttons">
		<?php echo BsHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->