<?php
/* @var $this RoutesController */
/* @var $model Routes */

$this->breadcrumbs=array(
	'Маршруты'=>array('index'),
	$model->id_route=>array('view','id'=>$model->id_route),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список маршрутов', 'url'=>array('index')),
	array('label'=>'Добавить маршрут', 'url'=>array('create')),
	array('label'=>'Просмотреть маршрут', 'url'=>array('view', 'id'=>$model->id_route))
);
?>

<h1>Редактировать маршрут</h1>

<?php $this->renderPartial('_form', array(
		'model'=>$model,
		'countries'=>$countries,
		'regions_1'=>$regions_1,
		'regions_2'=>$regions_2,
		'id_country_1'=>$id_country_1,
		'id_region_1'=>$id_region_1,
		'id_country_2'=>$id_country_2,
		'id_region_2'=>$id_region_2,
		'rims'=>$rims,
)); ?>