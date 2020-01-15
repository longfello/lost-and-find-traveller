<?php
/* @var $this LostServiceController */
$cs=Yii::app()->clientScript;
$cs->registerCssFile($baseUrl.'/css/lostservice.css');
$cs->registerScriptFile('/js/service.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/lostservice.js', CClientScript::POS_HEAD);

global $days_of_week;
$language = Yii::app()->language;
?>

<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
</div>
<?php endif; ?>

<h1 id="top" style="color:#58595b;margin-top: 20px;margin-bottom: 30px;"> <?=Yii::t('poputchik', 'Сообщить о находке')?></h1>
<div id="ifind" class="form ifind ext">

	
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'poputchik-ifind-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>
		<div class="row id_thing">
			<?php echo $form->labelEx($model,'id_thing'); ?>
			<div class="inner" >
			<?php echo $form->textField($model,'id_thing',array('maxlength'=>11)); ?>
			<?php echo $form->error($model,'id_thing'); ?>
			<div class="errorMessage" style="display:none;"></div></div>
		</div>
		<div class="row name">
			<?php echo $form->labelEx($model,'name'); ?>
			<div class="inner">
			<?php echo $form->textField($model,'name'); ?>
			<?php echo $form->error($model,'name'); ?>
			<div class="errorMessage" style="display:none;"></div></div>
		</div>
		<div class="row contact_phone">
			<?php echo $form->labelEx($model,'contact_phone'); ?>
			<div class="inner">
			<?php echo $form->textField($model,'contact_phone'); ?>
			<?php echo $form->error($model,'contact_phone'); ?>
			<div class="errorMessage" style="display:none;"></div></div>
		</div>
		<?php if(CCaptcha::checkRequirements()): /*проверка загружена ли каптча*/ ?>
		
		<div class="row captcha">
		  <?php echo $form->labelEx($model,'verifyCode'); /*вывод текстовой метки verifyCode*/?>
		  <div>
			<?php $this->widget('CCaptcha'); /*выводим саму каптчу*/?>
			<div id="cap_field" > = 
				<div style="position: relative; display:inline-block;">
					<?php echo $form->textField($model,'verifyCode'); /*выводим текстовое поле для ввода каптчи*/?> 
					<div class="hint" >
						<?php $options = array('class'=>'error errorMessage'); if($model->getError('verifyCode'))$options['style']='display:block;  ';  echo $form->error($model,'verifyCode', $options); /*ошибка при вводе каптчи*/?>
				    </div>
				</div>			
		    </div>
	      </div>
		</div>
		<?php endif; ?>

		<div class="clearfix"></div>
		<div class="row buttons">
			<div class="inner"><?php echo CHtml::submitButton(Yii::t('common', 'Отправить') ); ?><div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
		</div>

<?php $this->endWidget(); ?>


</div><!-- form -->
<div id="right"  class='right_column'>

<?php
		$this->widget('application.extensions.service_menu.service_menu',
		array('buttons'=>array( 
							
								array('idx'=>'5','href'=>'#'),
								array('idx'=>'4','href'=>'#'),	
		) )); 
?>
</div>
 <div class="block-clearfix"></div>


<!-- form -->


