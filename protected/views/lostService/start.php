<?php
/* @var $this LostServiceController */
$cs=Yii::app()->clientScript;
$cs->registerCssFile($baseUrl.'/css/lostservice.css');
$cs->registerScriptFile('/js/lostservice.js', CClientScript::POS_HEAD);

$language = Yii::app()->language;
?>

	

<h1 id="top" style="color:#58595b;margin-top: 20px;margin-bottom: 30px;"> <?=Yii::t('poputchik', 'Регистрация')?></h1>
<div id="start" class="form start">

	<?php if(	$params['showID']==0): ?>
		<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'poputchik-start-form',
		'enableAjaxValidation'=>false,
		)); ?>	
			<?php echo $form->errorSummary($user); ?>
			<?php echo $form->errorSummary($profile); ?>
		<div class="row second_name">
			<?php echo $form->labelEx($profile,'second_name'); ?>
			<?php echo $form->textField($profile,'second_name',array('value'=>$profile->second_name,'maxlength'=>50)); ?>
			<?php echo $form->error($profile,'second_name'); ?>
		</div>
		<div class="row first_name">
			<?php echo $form->labelEx($profile,'first_name'); ?>
			<?php echo $form->textField($profile,'first_name',array('value'=>$profile->first_name,'maxlength'=>50)); ?>
			<?php echo $form->error($profile,'first_name'); ?>
		</div>
		
		<div class="row username">
			<?php echo $form->labelEx($user,'username'); ?>
			<?  $arr = array('value'=>$user->username); if($params['isReadonly']==1)$arr['readonly']=true;   ?>
			
			<?php echo $form->textField($user,'username',$arr); ?>
			<?php echo $form->error($user,'username'); ?>
			<div style="position: absolute;display: block;left: 360px; ">
			<div class="error errorMessage" id="error">Номер телефона уже используется! <a id="ligin_sms" href="#">Отправить ID по sms?</a></div>
			<div class="error errorMessage" id="error2">Данное поле обязательно для заполнения!</a></div>
			<div  style="left: -80px;" class="success" id="success">Данный логин свободен!</div>
			</div>
		</div>
		<div class="row second_phone">
			<?php echo $form->labelEx($profile,'second_phone'); ?>
			<?php echo $form->textField($profile,'second_phone',array('value'=>$profile->second_phone)); ?>
			<?php echo $form->error($profile,'second_phone'); ?>
		</div>
		<div class="row email">
			<?php echo $form->labelEx($user,'email'); ?>
			<?php echo $form->emailField($user,'email',array('value'=>$user->email)); ?>
			<?php echo $form->error($user,'email'); ?>
		</div>
		
		<?php if( $params['isCaptcha']==1 && CCaptcha::checkRequirements()): /*проверка загружена ли каптча*/ ?>
		<div class="row captcha">
		  <?php echo $form->labelEx($user,'verifyCode'); /*вывод текстовой метки verifyCode*/?>
		  <div>
			<?php $this->widget('CCaptcha'); /*выводим саму каптчу*/?>
			<div id="cap_field" > = 
				<div style="position: relative; display:inline-block;">
					<?php echo $form->textField($user,'verifyCode'); /*выводим текстовое поле для ввода каптчи*/?> 
					<div class="hint" >
						<?php $options = array('class'=>'error errorMessage'); if($user->getError('verifyCode'))$options['style']='display:block;  ';  echo $form->error($user,'verifyCode', $options); /*ошибка при вводе каптчи*/?>
				    </div>
				</div>
			</div>
		  </div>
		 
		</div>
		<?php endif; ?>
		
		<div class="row confirm">
			<?php echo CHtml::checkBox('confirm_box', false); ?>
			Я согласен с 
			<?php echo CHtml::link('условиями соглашения', '#', array('onclick' => '$("#mydialog").dialog("open"); return false;',)); ?>
	
			<div class="clearfix"></div>
			<div class="row buttons">
				<div class="inner"><?php echo CHtml::submitButton(Yii::t('common', 'Получить ID'),array('id'=>'start-submit','disabled'=>true) ); ?>
				<div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
			</div>

			<?php $this->endWidget();  ?>
		  </div>
	<?php else: ?>
				<div class="user_lost_id">Ваш ID:  <?=Yii::app()->general->userid_to_idthing($user->id);  ?></div>		
	<?php endif; ?>
	<?php 
				$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                    'id' => 'mydialog',
                    'options' => array(
                        'title' => 'Условия соглашения',
                        'autoOpen' => false,
                        'modal' => true,
                        'resizable'=> false
                    ),
                ));
					
				echo "<p>Лял яля ля ялял яяя</p>";
      
                $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>

</div>

<div id="right"  class='right_column' >

<?php
		$this->widget('application.extensions.service_menu.service_menu',
		array('buttons'=>array(	
								
								array('idx'=>'4','href'=>'#'),
								
						
		) )); 
?>
</div>
 <div class="block-clearfix"></div>



