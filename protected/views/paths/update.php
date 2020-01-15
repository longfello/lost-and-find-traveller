<?php
/* @var $this PathsController */
/* @var $model Paths */

$this->breadcrumbs=array(
	'Пути'=>array('index'),
	$model->id_path=>array('view','id'=>$model->id_path),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Пути', 'url'=>array('index')),
	array('label'=>'Добавить путь', 'url'=>array('create')),
	array('label'=>'Просмотреть путь', 'url'=>array('view', 'id'=>$model->id_path)),
);
?>

<h1>Обновление пути</h1>

<?php $this->renderPartial('_form', array(
			'model'=>$model,
			'countries'=>$countries,
			'regions_1'=>$regions_1,
			'regions_2'=>$regions_2,
			'id_country_1'=>$id_country_1,
			'id_region_1'=>$id_region_1,
			'id_country_2'=>$id_country_2,
			'id_region_2'=>$id_region_2,
	));
?>