<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=true_or_false"></script>
<?php
/* @var $this LostFoundController */
/* @var $model LostFound */
/* @var $form CActiveForm */
$cs=Yii::app()->clientScript;
$cs->registerScriptFile('/js/globalize/globalize.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/globalize/globalize.culture.ru-RU.js', CClientScript::POS_HEAD);

$cs->registerScriptFile('/js/jquery.ui.core.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/jquery.ui.mask.js', CClientScript::POS_HEAD);


$cs->registerScriptFile('/js/kladr/jquery.kladr.min.js', CClientScript::POS_HEAD);
$cs->registerCssFile($baseUrl.'/js/kladr/jquery.kladr.min.css');

$cs->registerScriptFile('/js/jquery.ui.timepicker.js', CClientScript::POS_HEAD);

$cs->registerScriptFile('/js/lostservice-create.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/service.js', CClientScript::POS_HEAD);


$cs->registerCssFile($baseUrl.'/css/form.css');
$cs->registerCssFile($baseUrl.'/css/lostservice.css');
$countries = Countries::model()->findAll(array('order'=>'name'));
if($model->id_settlement)
	$def_city = Settlements::model()->findByPk($model->id_settlement);
if( $_GET['type_order'] ) $model->lost_or_found = $_GET['type_order'];

if($model->category!="")
	$def_cat = Category::model()->findByPk($model->category)->title;
?>

<div class="form ext">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lost-found-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>	
	<?php echo $form->errorSummary($model); ?>

	<div class="person-block">
		<div class="i-block"><img src="/images/poputchik/i-<?=Yii::app()->language?>.png" /></div>
		<div class="passenger"></div>
		<div class="driver"></div>
		<a class="passenger" rel="1" href="#"><?=Yii::t('lostfound', 'Нашёл')?></a>
		<a class="driver" rel="0" href="#"><?=Yii::t('lostfound', 'Потерял')?></a>
		<?php echo $form->hiddenField($model,'lost_or_found',array('value'=>$_GET['type_order'] )); ?>
	</div>
	

	<div class="row" style="display: block; height: 20px;">

		<div class="inner" >
			<?php echo $form->hiddenField($model,'category',array( 'id' => 'cat_hidden')); ?>
			<div id="c_cat" style="float: left;" class=""><a  href='#' ><? ( $def_cat =="")?print('Выбрать категорию'):print($def_cat );?> </a></div>
			<div class="del-icon" style="<? ( $def_cat=="")?print('display:none;'):print('display:inline-block;');?>  height: 20px;  margin-left: 10px;" onclick="$('.del-icon').hide(); $('#cat_hidden').attr('value', '' );$('#c_cat a').html('Выбрать категорию'); "><a style="display: inline-block; position:static; margin:0;" class="del"></a>
				<div class="del-label" style="left: -2px;position: relative;top: -7px;" id="<?=$order->id_order?>">Очистить</div>
			</div>
			<div class="errorMessage" style="bottom:20px; display:none;"></div>
		</div>
		
		<div id="cat" class="overlay"></div>
		<div class="my_popup">
		<table class="category capitalize">
		<?php  
		
			$count = ceil(count($categories)/3) ;
			
			for($i=0; $i<$count; $i++){
				echo "<tr>
				<td ><a href='#' class='cat_link' rel='".@$categories[$i]['id_cat']."'>".@$categories[$i]['title']."</a></td>
				<td ><a href='#' class='cat_link' rel='".@$categories[$i+$count]['id_cat']."'>".@$categories[$i+$count]['title']."</a></td>
				<td><a href='#' class='cat_link' rel='".@$categories[$i+2*$count]['id_cat']."'>".@$categories[$i+2*$count]['title']."</a></td>
				</tr>" ;
			}		
		
		?></table></div>
		
	</div><div class="block-clearfix"></div>

	<div class="row thing">
		<?php echo $form->labelEx($model,'thing'); ?>
		<div class="inner"><?php echo $form->textField($model,'thing',array('size'=>60,'maxlength'=>70)); ?>
		<?php echo $form->error($model,'thing'); ?>
		<div class="errorMessage" style="display:none;"></div></div>
	</div><div class="block-clearfix"></div>


	<div class="row comment">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('rows'=>6,'size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'comment'); ?>
		
	</div><div class="block-clearfix"></div>
	<fieldset style=" margin: 10px 0px 10px 0px;">
    <div id="legend-place" class="legend">Место потери</div>
	
	 <div class="field">
      
        <? /* <input id="settl" name="location" type="text" value=""> */?>
 
		<?/*	
	
	<div id="country_1-row" class="row">
		
			 <?php echo CHtml::dropDownList('country_1', '', CHtml::listData($countries, 'id_country', 'name'),
				array(
					'empty' => Yii::t('poputchik', "Выберите страну"),
					/*'ajax' => array(
						'type'=>'POST', //request type
						'url'=>CController::createUrl('regions/getRegionsByCountry'), //url to call.
						'update'=>'#region_1', //selector to update
						'data' => array(
							'id_country' => 'js:this.value',
						),
						)//leave out the data key to pass all form values through
				)			
			) ; ?>
		</div> 
		
		
	<div id="region_1-row" class="row clearfix">
			<?php echo CHtml::label(Yii::t('poputchik', Yii::t('poputchik', 'Регион')),'region_1'); ?>
			<?php echo CHtml::dropDownList('region_1', $id_region_1, $regions_1 ? CHtml::listData($regions_1, 'id_region', 'name') : array(),
				array(
					'empty' => Yii::t('poputchik', "Выберите регион"),
				)		
			) ; ?>
		</div>
		*/?></div><div class="block-clearfix"></div>
		
		<div id="start_settlement-wrapper" class="row">
			<?php echo CHtml::label(Yii::t('poputchik', 'Населённый пункт'),'id_settlement', array('required' => true)); ?>
			   <div class="inner"><?php echo CHtml::textField(  'settlement_name_1','',array( 'size' => 200, 'maxlength' => 255, 'class' => 'g_autocomplete')); ?>
			<?php echo  $form->hiddenField($model,'id_settlement',array('id'=>'id_settlement')); ?>
			<div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
		</div>
		
		<div id="id_sa_start-wrapper" class="sa row clearfix" style="display:none;">
			<?php echo $form->labelEx($model,'id_sa_settlement'); ?>
				<div class="inner"><?php echo $form->dropDownList($model,'id_sa_settlement', $sa_1 ? CHtml::listData($sa_1, 'id_sa', 'name') : array()); ?>
			<?php echo $form->error($model, 'id_sa_settlement'); ?>
			<div class="errorMessage" style="display:none;"></div></div>
		</div><div class="block-clearfix"></div>
	<div class="row">
		<?php echo $form->labelEx($model,'place_disc'); ?>
		<?php echo $form->textfield($model,'place_disc',array('size'=>55,'maxlength'=>55)); ?>
		<?php echo $form->error($model,'place_disc'); ?>
			
	</div><div class="block-clearfix"></div>

	</fieldset>
	
		<div id="date_from-wrapper" class="row">
			<?php echo CHtml::label(Yii::t('lostfound', 'Дата'),'LostFound_date_lf', array('required' => true)); ?>
			<div class="inner"><?php 
				$form->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model' => $model,
					'attribute' => 'date_lf',
					'language' => 'ru',
					'htmlOptions' => array(
						'size' => '10',         // textField size
						'maxlength' => '10',    // textField maxlength
					),
				)); 
			?>
			<?php echo $form->error($model,'date_lf'); ?>
			<div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
		</div>

	<div class="row photo">
		<?php echo $form->labelEx($model,'photo'); ?>
		<div class="upload">
		<input type="hidden" name="MAX_FILE_SIZE" value="5100000" />
		<?php echo $form->fileField($model,'photo'); ?>
		</div>	
		<?php echo $form->error($model,'photo'); ?>
		<div id="filename" class="filename"></div>
	</div><div class="block-clearfix"></div>


	<div class="row name"> 
	<span class='label'>Имя <span class="required">*</span></span>
		<div class="inner"><?php echo CHTML::textField('first_name',$user_data['first_name'],array('size'=>28,'maxlength'=>28,id=>'first_name'   )); ?>
		<div class="errorMessage" style="display:none;"></div>
	</div></div>
	<div class="row phone">
		<?php echo $form->labelEx($model,'phone'); ?>
		<div class="inner"><span class="plus">+</span><?php echo $form->textField($model,'phone',array('size'=>28,'maxlength'=>20   )); ?>
		<?php echo $form->error($model,'phone'); ?>
		<div class="errorMessage" style="display:none;"></div>
	</div></div><div class="block-clearfix"></div>
		<div class="row" style="float:right;">
			<span class='label'>E-mail</span>
			<?php if(!isset($user_data['email']) || $user_data['email']=="") 
					echo CHTML::emailField('email',$user_data['email'],array('size'=>20,id=>'email')); 
				  else echo "<span>".$user_data['email']."</span>"; 
            ?>
			<div class="errorMessage" style="display:none;"></div>
			
			
		
		</div><div class="block-clearfix"> </div>
	<div class="row buttons">
		<div class="inner"><?php echo CHtml::submitButton($model->isNewRecord ? 'Отправить' : 'Сохранить'); ?><div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->