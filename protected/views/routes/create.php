<?php
/* @var $this RoutesController */
/* @var $model Routes */

$this->breadcrumbs=array(
	'Маршруты'=>array('index'),
	'Добавление',
);

$this->menu=array(
	array('label'=>'Список маршрутов', 'url'=>array('index')),
);
?>

<h1>Добавление маршрута</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'countries'=>$countries)); ?>