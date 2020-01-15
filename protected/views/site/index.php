<?php
/* @var $this SiteController */
$cs=Yii::app()->clientScript;
// $cs->registerCssFile('/css/poputchik.css');
$cs->registerScriptFile('/js/poputchik.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/service.js', CClientScript::POS_HEAD);
$this->pageTitle=Yii::t('general', 'Единая информационная служба');
$language = Yii::app()->language;


?>
<div class="top_row">
	<div id="services row">
		<div class="service col-sm-6 col-xs-24">
			<a href="http://<?=SERVICE_POPUTCHIK?>.<?=Yii::app()->params['domain']?>/">
				<div class="img"><img src="/images/services/poputchik.png" /></div>
				<div class="title"><?=Yii::t('general', 'Попутчик');?></div>
			</a>
			<div class="dsc"><?=$poputDsc?></div>
		</div>
		<div class="service col-sm-6 col-xs-24" >
			<a href="http://<?=SERVICE_NEPOTERAYKA?>.<?=Yii::app()->params['domain']?>/">
				<div class="img"><img src="/images/services/nepoteryaika.png" /></div>
				<div class="title"><?=Yii::t('general', 'Непотеряйка');?></div>
			</a>
			<div class="dsc"><?=empty($nepoteryaikaDsc)?'':$nepoteryaikaDsc?></div>
		</div>
		<div class="service col-sm-6 col-xs-24" >
			<a href="http://<?=SERVICE_BURO_NAHODOK?>.<?=Yii::app()->params['domain']?>/">
				<div class="img"><img src="/images/services/buroNahodok.png" /></div>
				<div class="title"><?=Yii::t('general', 'Бюро находок');?></div>
			</a>
			<div style="color: #800000;padding-top:10px;" class="dsc">В разработке</div>
		</div>
		<div class="service col-sm-6 col-xs-24" >
			<a href="http://<?=SERVICE_NOCHLEG?>.<?=Yii::app()->params['domain']?>/">
				<div class="img"><img src="/images/services/nochleg.png" /></div>
				<div class="title"><?=Yii::t('general', 'Ночлег');?></div>
			</a>
			<div style="color: #800000; padding-top:10px;" class="dsc">В разработке</div>
		</div>
	</div>
</div>

<div class="another_row">
	<div class="video">
	<iframe src="//player.vimeo.com/video/VIDEO_ID" width="570" height="250" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

	</div>
	
	<div id="static-banner">
	<a href="http://<?=SERVICE_NEPOTERAYKA?>.<?=Yii::app()->params['domain']?>/"><img src="/images/banner.png" ></a>
	</div>
</div>

<div class="clearfix"></div>
<div class="main-title"><?=$text->name;?></div>
<div class="main-text"><?=$text->content;?></div>

