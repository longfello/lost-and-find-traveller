<?php
/* @var $this PathsController */
/* @var $model Paths */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'paths-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<fieldset id="s1" name="s1">
		<legend>Пункт 1</legend>
		<div class="row">
			<?php echo CHtml::label('Страна','country_1'); ?>
			<?php echo CHtml::dropDownList('country_1', $id_country_1, CHtml::listData($countries, 'id_country', 'name'),
				array(
					'empty' => "Выберите страну",
					'ajax' => array(
						'type'=>'POST', //request type
						'url'=>CController::createUrl('regions/getRegionsByCountry'), //url to call.
						'update'=>'#region_1', //selector to update
						'data' => array(
							'id_country' => 'js:this.value',
						),
						//leave out the data key to pass all form values through
				))			
			) ; ?>
		</div>
		<div class="row">
			<?php echo CHtml::label('Регион','region_1'); ?>
			<?php echo CHtml::dropDownList('region_1', $id_region_1, $regions_1 ? CHtml::listData($regions_1, 'id_region', 'name') : array(),
				array(
					'empty' => "Выберите регион",
				)		
			) ; ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'id_settlement_1'); ?>
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
					'minLength'=>'3',
					'showAnim'=>'fold',
					'delay'=>500,
					'select' =>'js: function(event, ui) {
						this.value = ui.item.value;
						// записываем полученный id в скрытое поле
						$("#Paths_id_settlement_1").val(ui.item.id);
						return false;
					}',
				),
				'htmlOptions'=>array(
					'size'=>'50',
				),
				'value'=>$model->startSettlement->name,
			));	?>
			<?php echo $form->hiddenField($model,'id_settlement_1'); ?>
			<?php echo $form->error($model,'id_settlement_1'); ?>
		</div>
	</fieldset>

	<fieldset id="s2" name="s2">
		<legend>Пункт 2</legend>
		<div class="row">
			<?php echo CHtml::label('Страна','country_2'); ?>
			<?php echo CHtml::dropDownList('country_2', $id_country_2, CHtml::listData($countries, 'id_country', 'name'),
				array(
					'empty' => "Выберите страну",
					'ajax' => array(
						'type'=>'POST', //request type
						'url'=>CController::createUrl('regions/getRegionsByCountry'), //url to call.
						'update'=>'#region_2', //selector to update
						'data' => array(
							'id_country' => 'js:this.value',
						),
						//leave out the data key to pass all form values through
				))			
			) ; ?>
		</div>
		<div class="row">
			<?php echo CHtml::label('Регион','region_2'); ?>
			<?php echo CHtml::dropDownList('region_2', $id_region_2, $regions_2 ? CHtml::listData($regions_2, 'id_region', 'name') : array(),
				array(
					'empty' => "Выберите регион",
				)		
			) ; ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'id_settlement_2'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'name'=>'settlement_name_2',
				'source' =>'js:function(request, response) {
					$.getJSON("'.$this->createUrl('settlements/autocomplete').'", {
						term: request.term.split(/,s*/).pop(),
						region_id: $("#region_2").val()
					}, response);
				}',
				// additional javascript options for the autocomplete plugin
				'options'=>array(
					'minLength'=>'3',
					'showAnim'=>'fold',
					'delay'=>500,
					'select' =>'js: function(event, ui) {
						this.value = ui.item.value;
						// записываем полученный id в скрытое поле
						$("#Paths_id_settlement_2").val(ui.item.id);
						return false;
					}',
				),
				'htmlOptions'=>array(
					'size'=>'50',
				),
				'value'=>$model->endSettlement->name
			));	?>
			<?php echo $form->hiddenField($model,'id_settlement_2'); ?>
			<?php echo $form->error($model,'id_settlement_2'); ?>
		</div>
	</fieldset>

	<div class="row">
		<?php echo $form->labelEx($model,'distance'); ?>
		<?php echo $form->textField($model,'distance'); ?>
		<?php echo $form->error($model,'distance'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


<?php
Yii::app()->clientScript->registerScript('autocomplete', "
  jQuery('#settlement_name_1').data('autocomplete')._renderItem = function( ul, item ) {
    return $('<li></li>')
      .data('item.autocomplete', item)
      .append( '<a>' + item.label + '<br><span class=\"desc\">' + item.desc + '</span></a>' )
      .appendTo(ul);
  };
  jQuery('#settlement_name_2').data('autocomplete')._renderItem = function( ul, item ) {
    return $('<li></li>')
      .data('item.autocomplete', item)
      .append( '<a>' + item.label + '<br><span class=\"desc\">' + item.desc + '</span></a>' )
      .appendTo(ul);
  };",
  CClientScript::POS_READY
);
?>