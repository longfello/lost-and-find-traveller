<?php
    
$text_not_results = '<div class="not-results">'.Yii::t('poputchik', '<p>По вашему запросу Попутчиков не найдено.</p><p>Оставьте заявку <a href="http://'.SERVICE_POPUTCHIK.'.'.Yii::app()->params['domain'].'/poputchikOrder/addAdvert/type/driver" title="Заполнить заявку Водителя">Водителя</a> или <a href="http://'.SERVICE_POPUTCHIK.'.'.Yii::app()->params['domain'].'/poputchikOrder/addAdvert/type/passenger" title="Заполнить заявку Пассажира">Пассажира</a>.</p>').'</div>';

if(isset($pages)) {
	$current_page = isset($_GET['page'])?$_GET['page']:1;
	
	$start_page = $current_page - 4;
	if($start_page < 1) $start_page = 1;
	$end_page = $current_page + 5;
	if($end_page > $pages) $end_page = $pages;
	else if ($end_page < 10) $end_page = 10;
}
    ?>


<div class="poput-orders container-clearfix">

	<div class="application">
		<h2>Подать заявку</h2>
		<a href="/poputchikOrder/addAdvert/type/passenger" class="applicationOfPassengers">Подать заявку пассажира</a><a href="/poputchikOrder/addAdvert/type/driver" class="applicationOfDriver">Подать заявку водителя</a>
	<div class="clear"></div>
    </div>
	<div class="numberOfRoutes">
		<span>Найдено 167 смешанных маршрутов</span>
	</div>
			<?php if($orders): ?>
				<?php foreach($orders as $order): ?>
					<?php $this->renderPartial('/poputchikOrder/_order', array('order'=>$order)); ?>
				<?php endforeach; ?>
			<?php else: ?>
				<?= $text_not_results ?>
			<?php endif; ?>

			<?php if($pages): ?>
				<div class="pager"><div class="child"><div class="dop-block">
					<?php if($current_page > 1): ?><a class="prev" rel="<?=($current_page-1)?>" href="?search=<?=isset($search)?$search:''?>&page=<?=($current_page-1)?>"><?=Yii::t('general', 'Предыдущая')?></a><?php endif; ?>
					<?php for($i = $start_page; $i <= $end_page; $i++): ?>
						<a class="page<?php if($i == $current_page) print ' current';?>" rel="<?=$i?>" href="?search=<?=isset($search)?$search:''?>&page=<?=$i?>"><?=$i?></a>
					<?php endfor; ?>
					<?php if($current_page < $pages): ?><a class="next" rel="<?=($current_page+1)?>" href="?search=<?=isset($search)?$search:''?>&page=<?=($current_page+1)?>"><?=Yii::t('general', 'Следующая')?></a><?php endif; ?>
				</div></div></div>
			<?php endif; ?>
</div>
