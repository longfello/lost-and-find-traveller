<?php
/* @var $this PathsController */
/* @var $model Paths */

$this->breadcrumbs=array(
	'Пути'=>array('index'),
	'Добавление',
);

$this->menu=array(
	array('label'=>'List Paths', 'url'=>array('index')),
	array('label'=>'Manage Paths', 'url'=>array('admin')),
);
?>

<h1>Добавить путь</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'countries'=>$countries)); ?>