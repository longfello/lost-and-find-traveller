<?php
/* @var $this AutoModelsController */
/* @var $model AutoModels */

$this->breadcrumbs=array(
	'Модели автомобилей'=>array('index'),
	'Добавление',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Добавление</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'brands'=>$brands)); ?>