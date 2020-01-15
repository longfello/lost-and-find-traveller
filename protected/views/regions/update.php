<?php
/* @var $this RegionsController */
/* @var $model Regions */

$this->breadcrumbs=array(
	'Регионы'=>array('index'),
	$model->name=>array('view','id'=>$model->id_region),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список регионов', 'url'=>array('index')),
	array('label'=>'Добавить регион', 'url'=>array('create')),
	array('label'=>'Просмотреть регион', 'url'=>array('view', 'id'=>$model->id_region)),
	array('label'=>'Управление регионами', 'url'=>array('admin')),
);
?>

<h1>Редактировать регион</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'countries'=>$countries, 'socrnames'=>$socrnames)); ?>