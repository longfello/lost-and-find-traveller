<?php
/* @var $this GeoZoneController */
/* @var $model GeoZone */

$this->breadcrumbs=array(
	'Области'=>array('index'),
	'Добавление',
);

$this->menu=array(
	array('label'=>'Список областей', 'url'=>array('index')),
);
?>

<h1>Добавить область</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>