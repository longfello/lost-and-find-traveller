<?php
/* @var $this SAreasController */
/* @var $model SAreas */
/* @var $form CActiveForm */
?>


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sareas-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'id_settlement'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'name'=>'settlement_name',
				'source'=>Yii::app()->createUrl('settlements/autocomplete'),
				// additional javascript options for the autocomplete plugin
				'options'=>array(
					'minLength'=>'3',
					'showAnim'=>'fold',
					'delay'=>500,
					'select' =>'js: function(event, ui) {
						this.value = ui.item.value;
						// записываем полученный id в скрытое поле
						$("#SAreas_id_settlement").val(ui.item.id);
						return false;
					}',
				),
				'htmlOptions'=>array(
					'size'=>'50',
				),
			));
		?>
		<?php echo $form->hiddenField($model,'id_settlement'); ?>
		<?php echo $form->error($model,'id_settlement'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript('autocomplete', "
  jQuery('#settlement_name').data('autocomplete')._renderItem = function( ul, item ) {
    return $('<li></li>')
      .data('item.autocomplete', item)
      .append( '<a>' + item.label + '<br><span class=\"desc\">' + item.desc + '</span></a>' )
      .appendTo(ul);
  };",
  CClientScript::POS_READY
);
?>