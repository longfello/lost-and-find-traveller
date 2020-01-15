<?php
/* @var $this SeoCityController */
/* @var $model SeoCity */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'seo-city-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        <?php 
        if($model->isNewRecord){
            ?>
        
        <div class="row">
		<label for="SeoCity_city_id" class="required">City <span class="required">*</span></label>
               <?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
			'name'=>'settlement_name_1',
			'source' =>'js:function(request, response) {
				$.getJSON("'.$this->createUrl('settlements/autocomplete').'", {
					term: request.term.split(/,s*/).pop(),
					region_id: $("#region_1").val()
				}, response);
				
			}',
			// additional javascript options for the autocomplete plugin
			'options'=>array(
				'minLength'=>'1',
				'showAnim'=>'fold',
				'delay'=>500,
				'select' =>'js: function(event, ui) {
					this.value = ui.item.value;
					// записываем полученный id в скрытое поле
					$("#SeoCity_city_id").val(ui.item.id);
					return false;
				}',
				'response' =>'js: autoCompleteResponse',
			),
			'htmlOptions'=>array(
				'size'=>'50',
				'defaultValue'=>Yii::t('poputchik', "Город")
			),
			'value'=>$model->settlements->name,
		));	?>
			<?php echo CHtml::hiddenField('SeoCity[city_id]', $model->city_id); ?>
                		
        </div>
             <?php } else {
                 
             }   
             ?><div class="row"><label for="SeoCity_city_id" class="required">City <span class="required">*</span></label><?=$model->settlements->name?></div>
        
	<div class="row">
		<?php echo $form->labelEx($model,'seo_text'); ?>
		<?php echo $form->textArea($model,'seo_text',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'seo_text'); ?>
	</div>
             <div class="row">
		<?php echo $form->labelEx($model,'seo_text_top'); ?>
		<?php echo $form->textArea($model,'seo_text_top',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'seo_text_top'); ?>
	</div>
             
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
             <div class="row">
		<?php echo $form->labelEx($model,'keywords'); ?>
		<?php echo $form->textField($model,'keywords',array('size'=>50,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'keywords'); ?>
	</div>
             <div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>50,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->