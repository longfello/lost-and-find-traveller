<?php
/* @var $this GeneralController */

$this->breadcrumbs=array(
	'General'=>array('/general'),
	'Cabinet',
);
$language = Yii::app()->language;
?>
<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
</div>
<?php endif; ?>
<h1><?=Yii::t('general', 'Личный кабинет')?></h1>
<ul>
	<li><a href="/user/profile/edit"><?=Yii::t('general', 'Мой профиль')?></a>
	<li><?=Yii::t('general', 'Мои объявления')?>
	<ul>
		<li><a href="/<?=$language?>/poputchikOrder/my"><?=Yii::t('poputchik', 'В Попутчике')?></a>
		<li><a href="/<?=$language?>/LostFound/my"><?=Yii::t('poputchik', 'В Бюро находок')?></a>
	</ul>
</ul>