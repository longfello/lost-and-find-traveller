<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - '.Yii::t('common','Ошибка').' '.$code;
$this->breadcrumbs=array(
    Yii::t('common','Ошибка').' '.$code,
);
?>

<div class="error_wrapper">
  <div class="error_background">
    <h2><?= Yii::t('common','Ошибка').' '.$code; ?></h2>
    <div class="error"><?php echo CHtml::encode($message); ?></div>
  </div>
    <img src="/images/404.jpg" alt=""/>
</div>