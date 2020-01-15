<?php
/* @var $this RoutesController */
/* @var $model Routes */
/* @var $form CActiveForm */
$cs=Yii::app()->clientScript;

// Add JS

$cs->registerScriptFile('/js/route.js', CClientScript::POS_HEAD);
if($_POST) $rims = $_POST;
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'routes-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
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
			<?php echo $form->labelEx($model,'start_settlement'); ?>
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
						$("#Routes_start_settlement").val(ui.item.id);
						return false;
					}',
				),
				'htmlOptions'=>array(
					'size'=>'50',
				),
				'value'=>$model->startSettlement->name,
			));	?>
			<?php echo $form->hiddenField($model,'start_settlement'); ?>
			<?php echo $form->error($model,'start_settlement'); ?>
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
			<?php echo $form->labelEx($model,'end_settlement'); ?>
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
						$("#Routes_end_settlement").val(ui.item.id);
						return false;
					}',
				),
				'htmlOptions'=>array(
					'size'=>'50',
				),
				'value'=>$model->endSettlement->name
			));	?>
			<?php echo $form->hiddenField($model,'end_settlement'); ?>
			<?php echo $form->error($model,'end_settlement'); ?>
		</div>
	</fieldset>

	<fieldset id="rims" name="rims">
		<legend>Промежуточные пункты в порядке следования</legend>
		<div class="row">
			<?php echo CHtml::label('Страна','country'); ?>
			<?php echo CHtml::dropDownList('country', $id_country, CHtml::listData($countries, 'id_country', 'name'),
				array(
					'empty' => "Выберите страну",
					'ajax' => array(
						'type'=>'POST', //request type
						'url'=>CController::createUrl('regions/getRegionsByCountry'), //url to call.
						'update'=>'#region', //selector to update
						'data' => array(
							'id_country' => 'js:this.value',
						),
						//leave out the data key to pass all form values through
				))			
			) ; ?>
		</div>
		<div class="row">
			<?php echo CHtml::label('Регион','region'); ?>
			<?php echo CHtml::dropDownList('region', $id_region, array()); ?>
		</div>
		<div class="row">
			<?php echo CHtml::label('Населённый пункт','settlement'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'name'=>'rim_settlement_name',
				'source' =>'js:function(request, response) {
					$.getJSON("'.$this->createUrl('settlements/autocomplete').'", {
						term: request.term.split(/,s*/).pop(),
						region_id: $("#region").val()
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
						$("#rim_settlement_id").val(ui.item.id);
						$("#rim_settlement_full_name").val(ui.item.label + "<br><span class=\"desc\">" + ui.item.desc + "</span>");
						$("#add_settlement").removeAttr("disabled");
						return false;
					}',
				),
				'htmlOptions'=>array(
					'size'=>'50',
				),
			));	?>
			<?php echo CHtml::hiddenField('rim_settlement_id'); ?>
			<?php echo CHtml::hiddenField('rim_settlement_full_name'); ?>
		</div>
		<div class="row">
			<input id="add_settlement" type="button" disabled="disabled" value="Добавить &gt;&gt;" />
		</div>
		<div class="row">
			<table id="route-paths" class="route-settlements-table">
				<?php for($i = 0; $i < count($rims['rim_sid']); $i++): ?>
					<tr>
						<td class="name">
							<?= urldecode($rims['rim_sname'][$i]) ?>
							<input type="hidden" name="rim_sname[]" value="<?=$rims['rim_sname'][$i]?>" />
							<input type="hidden" name="rim_sid[]" value="<?=$rims['rim_sid'][$i]?>" /></td>
						<td class="up-button button"><a href="#"><img src="/images/up-arrow.png" /></a></td>
						<td class="down-button button"><a href="#"><img src="/images/down-arrow.png" /></a></td>
						<td class="del-button button"><a href="#"><img src="/images/del-icon.png" /></a></td>
					</tr>
				<?php endfor; ?>
			</table>
		</div>
	</fieldset>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить'); ?>
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
  };
  jQuery('#rim_settlement_name').data('autocomplete')._renderItem = function( ul, item ) {
    return $('<li></li>')
      .data('item.autocomplete', item)
      .append( '<a>' + item.label + '<br><span class=\"desc\">' + item.desc + '</span></a>' )
      .appendTo(ul);
  };
  $('#add_settlement').click(function() {
	$('#route-paths').append(get_route_settlement_tr());
	$('#add_settlement').attr('disabled','disabled');
	$('#rim_settlement_name').val('');
  });
  ",
  CClientScript::POS_READY
);
?>