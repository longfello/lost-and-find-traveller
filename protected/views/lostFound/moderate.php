<?php
/* @var $this PoputchikOrderController */
/* @var $dataProvider CActiveDataProvider */

$language = Yii::app()->language;
?>

<h1><?=Yii::t('poputchik', 'Модерировать заявки')?></h1>

<div class="poput-orders ">

<?php foreach($orders as $order): ?>
<div class="admin_order_wrap">
<?php $this->renderPartial('_order', array('order'=>$order)); ?>

</div><input class="order_check" type="checkbox" name="orders[]"  id="order-<?=$order->id_lf ?>" value="<?=$order->id_lf ?>"/>
<?php endforeach; ?>
<div id="admin_buttons">
	<div id="apply_check" onclick="submit_check_orders(1);">Опубликовать выбранные</div>
	<div id="delete_check" onclick="submit_check_orders(2);">Удалить выбранные</div>
</div>
</div> 


<form name="check_orders" id="check_orders" method="post" style="display:none;">
<input type="hidden" name="returnUrl" value="<?=$_SERVER['REQUEST_URI']?>" />
</form>