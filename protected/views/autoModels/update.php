<?php
/* @var $this AutoModelsController */
/* @var $model AutoModels */

$this->breadcrumbs=array(
	'Модели автомобилей'=>array('index'),
	$model->id_model=>array('view','id'=>$model->id_model),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Добавить', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id_model)),
);
?>

<h1>Редактирование</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'brands'=>$brands)); ?>