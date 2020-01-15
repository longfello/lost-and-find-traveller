<?php
/* @var $this SettlementsController */
/* @var $model Settlements */

$this->breadcrumbs=array(
	'Населённые пункты'=>array('index'),
	'Добавление',
);

$this->menu=array(
	array('label'=>'Список населённых пунктов', 'url'=>array('index')),
	array('label'=>'Управление населёнными пунктами', 'url'=>array('admin')),
);
?>

<h1>Добавление населённого пункта</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'countries'=>$countries, 'regions'=>$regions, 'socrnames'=>$socrnames)); ?>