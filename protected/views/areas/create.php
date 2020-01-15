<?php
/* @var $this AreasController */
/* @var $model Areas */

$this->breadcrumbs=array(
	'Районы'=>array('index'),
	'Добавление',
);

$this->menu=array(
	array('label'=>'Список районов', 'url'=>array('index')),
	array('label'=>'Управление районами', 'url'=>array('admin')),
);
?>

<h1>Добавление района</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'regions'=>$regions, 'socrnames'=>$socrnames)); ?>