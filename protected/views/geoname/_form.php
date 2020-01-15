<?php
/* @var $this GeonameController */
/* @var $model Geoname */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('BsActiveForm', array(
	'id'=>'geoname-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->errorSummary($seoModel); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
		<?php echo $form->error($model,'id'); ?>
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
		<?php echo $form->labelEx($model,'alternatenames'); ?>
		<?php echo $form->textField($model,'alternatenames',array('size'=>60,'maxlength'=>4000)); ?>
		<?php echo $form->error($model,'alternatenames'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'latitude'); ?>
		<?php echo $form->textField($model,'latitude',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'latitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'longitude'); ?>
		<?php echo $form->textField($model,'longitude',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'longitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'population'); ?>
		<?php echo $form->textField($model,'population'); ?>
		<?php echo $form->error($model,'population'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'timezone'); ?>
		<?php echo $form->textField($model,'timezone',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'timezone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dia'); ?>
		<?php echo $form->textField($model,'dia'); ?>
		<?php echo $form->error($model,'dia'); ?>
	</div>

	<div class="row">
    <?php echo $form->labelEx($model,'slug'); ?>
    <?php echo $form->textField($model,'slug',array('size'=>60,'maxlength'=>200)); ?>
    <?php echo $form->error($model,'slug'); ?>
	</div>

  <hr>
  <h3>SEO</h3>
  <hr>

  <div class="row">
    <?php echo $form->labelEx($seoModel,'seo_text'); ?>
    <?php $this->widget('application.extensions.ckeditor.CKEditor', array(
          'model'=>$seoModel,
          'attribute'=>'seo_text',
          'editorTemplate'=>'basic',//'basic'
          'skin' => 'v2'
      )); ?>
    <?php echo $form->error($seoModel,'seo_text'); ?>
  </div>
  <div class="row">
    <?php echo $form->labelEx($seoModel,'seo_text_top'); ?>
    <?php $this->widget('application.extensions.ckeditor.CKEditor', array(
          'model'=>$seoModel,
          'attribute'=>'seo_text_top',
          'editorTemplate'=>'basic',//'basic'
          'skin' => 'v2'
      )); ?>
    <?php echo $form->error($seoModel,'seo_text_top'); ?>
  </div>
  <div class="row">
    <?php echo $form->labelEx($seoModel,'title'); ?>
    <?php echo $form->textField($seoModel,'title'); ?>
    <?php echo $form->error($seoModel,'title'); ?>
  </div>
  <div class="row">
    <?php echo $form->labelEx($seoModel,'description'); ?>
    <?php echo $form->textField($seoModel,'description'); ?>
    <?php echo $form->error($seoModel,'description'); ?>
  </div>
  <div class="row">
    <?php echo $form->labelEx($seoModel,'keywords'); ?>
    <?php echo $form->textField($seoModel,'keywords'); ?>
    <?php echo $form->error($seoModel,'keywords'); ?>
  </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->