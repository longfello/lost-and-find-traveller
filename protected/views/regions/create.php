<?php
/* @var $this RegionsController */
/* @var $model Regions */

$this->breadcrumbs=array(
	'Регионы'=>array('index'),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Управление областями', 'url'=>array('admin')),
);
?>

<h1>Добавить регион</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'countries'=>$countries, 'socrnames'=>$socrnames)); ?>