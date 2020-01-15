<?php
/* @var $this PoputchikOrderController */
/* @var $dataProvider CActiveDataProvider */
$cs=Yii::app()->clientScript;
// $cs->registerCssFile($baseUrl.'/css/poputchik.css');
$cs->registerScriptFile('/js/poputchik.js', CClientScript::POS_HEAD);
global $days_of_week;
$language = Yii::app()->language;
$text_not_results = '<div class="not-results">'.Yii::t('poputchik', "Вы еще не создали ни одной заявки.").'</div>';

if($pages) {
	$current_page = $_GET['page'];
	if(!$current_page) $current_page = 1;
	$start_page = $current_page - 4;
	if($start_page < 1) $start_page = 1;
	$end_page = $current_page + 5;
	if($end_page > $pages) $end_page = $pages;
	else if ($end_page < 10) $end_page = 10;
	
	
}



?>
<div id="anchor"></div>
<h1 style="text-align: center;"><?=Yii::t('poputchik', $title)?></h1>


<div   class='left_menu'>

<?php
		$this->widget('application.extensions.user_menu.user_menu',	array('active_button'=> "my_orders"	 )); 
?>
</div>


<div class="poput-orders container-clearfix cabinet">

	<?php if($orders): ?>
		<?php foreach($orders as $order): ?>
			<?php $this->renderPartial('/poputchikOrder/_order', array('order'=>$order,'itsmy'=>1)); ?>
		<?php endforeach; ?>
		<?php if($pages): ?>
		<div class="pager"><div class="child"><div class="dop-block">
			<?php if($current_page > 1): ?><a class="prev" rel="<?=($current_page-1)?>" href="?page=<?=($current_page-1)?>"><?=Yii::t('general', 'Предыдущая')?></a><?php endif; ?>
			<?php for($i = $start_page; $i <= $end_page; $i++): ?>
				<a class="page<?php if($i == $current_page) print ' current';?>" rel="<?=$i?>" href="?page=<?=$i?>"><?=$i?></a>
			<?php endfor; ?>
			<?php if($current_page < $pages): ?><a class="next" rel="<?=($current_page+1)?>" href="?page=<?=($current_page+1)?>"><?=Yii::t('general', 'Следующая')?></a><?php endif; ?>
		</div></div></div>
		<?php endif; ?>
		
	<?php else: ?>
		<?= $text_not_results ?>
	<?php endif; ?>

  <div id="fixed-menu" style="bottom: 0px;">

    <div class="added" > 	</div>
    <a class="left-link" href="/poputchikOrder/addAdvert/type/passenger">Подать заявку пассажира</a>
    <a class="up-link" href="#anchor"></a>
    <a class="right-link" href="/poputchikOrder/addAdvert/type/driver">Подать заявку водителя</a>

  </div>

	</div>
		

