<?php
/* @var $this PoputchikOrderController */
/* @var $dataProvider CActiveDataProvider */
$cs=Yii::app()->clientScript;
// $cs->registerCssFile('/css/poputchik.css');
$cs->registerScriptFile('/js/poputchik.js', CClientScript::POS_HEAD);
global $days_of_week;
$language = Yii::app()->language;
?>

<h1><?=Yii::t('poputchik', 'Модерирование заявок попутчика')?></h1>

<input type="hidden" name="returnUrl" value="<?=$_SERVER['REQUEST_URI']?>" />
<div class="poput-orders container-clearfix">

<?php foreach($orders as $order): ?>
<div class="admin_order_wrap">
<?php $this->renderPartial('_order', array('order'=>$order)); ?>

</div><input class="order_check" type="checkbox" name="orders[]"  id="order-<?=$order->id_order ?>" value="<?=$order->id_order ?>"/>
<?php endforeach; ?>
<div id="admin_buttons">
	<div id="apply_check" onclick="submit_check_orders(1);">Опубликовать выбранные</div>
	<div id="delete_check" onclick="submit_check_orders(2);">Удалить выбранные</div>
</div>

</div>

<form name="check_orders" id="check_orders" method="post" style="display:none;">
<input type="hidden" name="returnUrl" value="<?=$_SERVER['REQUEST_URI']?>" />
</form>


<?php

?>