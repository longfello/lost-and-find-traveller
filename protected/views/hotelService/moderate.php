<?php
/* @var $this PoputchikOrderController */
/* @var $dataProvider CActiveDataProvider */

$cs=Yii::app()->clientScript;
$cs->registerCssFile($baseUrl.'/css/hotel_service.css');
$cs->registerScriptFile('/js/service.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/jquery.cookie.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/rate/jquery.raty.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/hotelservice.js', CClientScript::POS_HEAD);
$cs->registerCssFile($baseUrl.'/js/rate/jquery.raty.css');

if($pages) {
    $current_page = $_GET['page'];
    if(!$current_page) $current_page = 1;
    $start_page = $current_page - 4;
    if($start_page < 1) $start_page = 1;
    $end_page = $current_page + 5;
    if($end_page > $pages) $end_page = $pages;
    else if ($end_page < 10) $end_page = 10;
}


$text_not_results="<div style='text-align:center;'>По вашему запросу ничего не найдено.</div>";
global $cookie ;
 $cookie = (isset(Yii::app()->request->cookies['id_hs']->value)) ? json_decode( Yii::app()->request->cookies['id_hs']->value, true)   : '';


$colorbox = $this->widget('application.extensions.colorpowered.JColorBox');
$colorbox->addInstance('.cbox');






?>



    <div class="wraper-for-marging">
        <div class="left_column" style="width:580px;">
		
			<div class="header"><div id="anchor"></div><h1><?=Yii::t('poputchik', 'Модерирование заявок сервиса Ночлег')?></h1></div>
			   <div class="lf-orders container-clearfix">
					<?php foreach($orders as $order): ?>
					<?php $this->renderPartial('_order', array('order'=>$order)); ?>
					<?php endforeach; ?>
				</div>
		</div><div class="clearfix"></div>
	</div>
<?php

?>