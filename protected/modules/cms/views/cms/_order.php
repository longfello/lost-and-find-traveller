<?php
/* @var $this PoputchikOrderController */
/* @var $dataProvider CActiveDataProvider */
$cs=Yii::app()->clientScript;

$language = Yii::app()->language;


?>

<div id="annotation-<?=$order->id?>" class="annotation clearfix">

<h3><?=$order->title?></h3>
	
		<?=$order->annotation?>			
	<div class="legend_readmore"><a class="readmore" href="<?=$order->url?>"> Читать далее...		</a></div>	
	</div>




