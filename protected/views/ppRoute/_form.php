<?php
/* @var $this PpRouteController */
/* @var $model PpRoute */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pp-route-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
));

  $language = Yii::app()->getLanguage();
  $dateFormat = 'yy-mm-dd';

  //date format is set from i18n defaults, override it here
  $js = "jQuery.datepicker.regional['$language'].dateFormat = '$dateFormat';";
  Yii::app()->getClientScript()->registerScript('setDateFormat', $js);

?>


	<?php echo $form->errorSummary($model); ?>

	<div class="row">
    <?php echo $form->labelEx($model,'path'); ?>
    <strong>
		<?php echo $model->fromLocation->name.' &rarr; '.$model->toLocation->name; ?>
    </strong>
    <ul>
		  <?php
        $locations = $model->getRouteLocations();
        array_map(function($el){ echo "<li>".$el['name']."</li>"; }, $locations['near']);
      ?>
    </ul>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'departure'); ?>
    <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'departure',
        'model' => $model,
        'attribute' => 'departure',
        'language' => 'ru',
        'options' => array(
            'showAnim' => 'fold',
            'dateFormat'=>$dateFormat,
        ),
        'htmlOptions' => array('style' => 'height:20px;'),
    )); ?>
    <?php echo $form->error($model,'departure'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'return'); ?>
    <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'return',
        'model' => $model,
        'attribute' => 'return',
        'language' => 'ru',
        'options' => array(
            'showAnim' => 'fold',
            'dateFormat'=>$dateFormat,
        ),
        'htmlOptions' => array('style' => 'height:20px;'),
    )); ?>
		<?php echo $form->error($model,'return'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cost'); ?>
		<?php echo $form->textField($model,'cost'); ?>
		<?php echo $form->error($model,'cost'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'free_seats'); ?>
		<?php echo $form->dropDownList($model,'free_seats', array(0,1,2,3,4)); ?>
		<?php echo $form->error($model,'free_seats'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'luggage'); ?>
		<?php echo $form->dropDownList($model,'luggage', EnumRouteLuggage::getDataForDropDown()); ?>
		<?php echo $form->error($model,'luggage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'punctuality'); ?>
		<?php echo $form->dropDownList($model,'punctuality',EnumRoutePunctuality::getDataForDropDown()); ?>
		<?php echo $form->error($model,'punctuality'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deviation_from_route'); ?>
		<?php echo $form->dropDownList($model,'deviation_from_route',EnumRouteDeviation::getDataForDropDown()); ?>
		<?php echo $form->error($model,'deviation_from_route'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'available_until'); ?>
    <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'available_until',
        'model' => $model,
        'attribute' => 'available_until',
        'language' => 'ru',
        'options' => array(
            'showAnim' => 'fold',
            'dateFormat'=>$dateFormat,
        ),
        'htmlOptions' => array('style' => 'height:20px;'),
    )); ?>
		<?php echo $form->error($model,'available_until'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type',EnumRouteType::getDataForDropDown()); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'enabled'); ?>
		<?php echo $form->dropDownList($model,'enabled', array('0'=>'Нет', '1'=>'Да')); ?>
		<?php echo $form->error($model,'enabled'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->