<?php
/* @var $this PoputchikOrderController */
/* @var $dataProvider CActiveDataProvider */
$cs=Yii::app()->clientScript;
$cs->registerCssFile($baseUrl.'/css/lostservice.css');
$cs->registerScriptFile('/js/lostservice.js', CClientScript::POS_HEAD);
$language = Yii::app()->language;
?>

<h1><?=Yii::t('lostservice', 'Модерирование сообщений о находках')?></h1>

<div class="poput-orders container-clearfix">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'LostServiceFilter',	
	'enableAjaxValidation'=>false,
)); ?>	
		<label for="status0" >Новые</label>			
		<input type="checkbox" id="status0" name="filters[status0]" <?php 	if(isset($filters['status0'])) echo "checked";?>>
		<label for="status1" >В исполнении</label>
		<input type="checkbox" id="status1" name="filters[status1]"<?php 	if(isset($filters['status1'])) echo "checked";?>>
		<label for="status2" >Закрытые</label>
		<input type="checkbox" id="status2" name="filters[status2]"<?php 	if(isset($filters['status2'])) echo "checked";?>>
		<label for="LostService_id_thing" >По ИД</label>
		<input type="textfield" id="LostService_id_thing" name="filters[id]" maxlength='11' <?php 	if(isset($filters['id'])) echo "value='".$filters['id']."'";?>>
		<label for="LostService_phone" >По Тел. потерявшего</label>
		<input type="textfield" id="LostService_phone" name="filters[phone]" maxlength='13' <?php 	if(isset($filters['phone'])) echo "value='".$filters['phone']."'";?>>
			
	
		
		<div class="clearfix"></div>
		<div class="row buttons">
		<div class="inner"><?php echo CHtml::submitButton(Yii::t('common', 'Фильтровать') ); ?></div>
		</div>

<?php $this->endWidget(); ?>

    <table border="1" cellpadding="4" cellspacing="0">
    <tr>
		<th>Операция</th> <th>Номер</th><th>Статус</th><th>Код подтверждения</th><th>Идентификатор</th><th>Имя нашедшего</th><th>Телефон нашедшего</th><th>Имя потерявшего</th><th>Телефоны потерявшего</th><th>Комментарий</th>
	</tr>
	<?php $i=0; foreach($requests as $request): ?>
	<?php $i++; $this->renderPartial('_requst', array('request'=>$request,'idx'=>$i)); ?>
	<?php endforeach; ?> 
	</table>
</div>
<?php

?>